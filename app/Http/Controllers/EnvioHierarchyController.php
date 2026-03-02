<?php

namespace App\Http\Controllers;

use App\Models\Conteudo;
use App\Models\Envio;
use App\Models\Publicacao;
use App\Models\Volume;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class EnvioHierarchyController extends Controller
{
    /**
     * Display the envio hierarchy with accordion interface
     */
    public function index(Request $request)
    {
        $congregacaoId = congregacaoAtivaId();
        $search = $request->input('search');
        $searchTerm = trim((string) $search);
        $normalizedSearch = mb_strtolower($searchTerm);
        $perpage = $request->input('perpage', 10);
        
        // Limpa os filtros da sessão se o acesso for direto (GET sem parâmetros)
        if (empty($request->query()) && $request->method() == 'GET') {
            $request->session()->forget(['envio_hierarchy_search', 'envio_hierarchy_perpage']);
        }

        // Lógica para o filtro de busca
        if ($request->has('search')) {
            $search = $request->input('search');
            $request->session()->put('envio_hierarchy_search', $search);
        } elseif ($request->session()->exists('envio_hierarchy_search')) {
            $search = $request->session()->get('envio_hierarchy_search');
        }

        // Lógica para o número de itens por página (perpage)
        if ($request->has('perpage')) {
            $perpage = $request->input('perpage');
            $request->session()->put('envio_hierarchy_perpage', $perpage);
        } elseif ($request->session()->exists('envio_hierarchy_perpage')) {
            $perpage = $request->session()->get('envio_hierarchy_perpage');
        }
        
        $enviosQuery = Envio::where('congregacao_id', $congregacaoId)
            ->with([
                'volumes' => function ($query) {
                    $query->orderBy('id');
                },
                'volumes.conteudos' => function ($query) {
                    $query->with('publicacao')->orderBy('id');
                }
            ])
            ->orderByDesc('data');

        // Filtrar por busca se fornecida
        if ($searchTerm !== '') {
            $enviosQuery->where(function($q) use ($searchTerm) {
                $q->where('nota', 'like', "%{$searchTerm}%")
                  ->orWhereHas('volumes', function($v) use ($searchTerm) {
                      $v->where('volume', 'like', "%{$searchTerm}%")
                        ->orWhereHas('conteudos.publicacao', function($p) use ($searchTerm) {
                            $p->where('nome', 'like', "%{$searchTerm}%")
                              ->orWhere('codigo', 'like', "%{$searchTerm}%");
                        });
                  });
            });
        }

        $envios = $enviosQuery->paginate($perpage);

        if ($searchTerm !== '') {
            $envios->getCollection()->transform(function ($envio) use ($normalizedSearch) {
                $nota = mb_strtolower((string) ($envio->nota ?? ''));
                $notaMatches = $nota !== '' && mb_strpos($nota, $normalizedSearch) !== false;

                if ($notaMatches) {
                    return $envio;
                }

                $filteredVolumes = $envio->volumes->filter(function ($volume) use ($normalizedSearch) {
                    $volumeName = mb_strtolower((string) ($volume->volume ?? ''));
                    $volumeMatches = $volumeName !== '' && mb_strpos($volumeName, $normalizedSearch) !== false;

                    if ($volumeMatches) {
                        return true;
                    }

                    $matchingConteudos = $volume->conteudos->filter(function ($conteudo) use ($normalizedSearch) {
                        $publicacaoNome = mb_strtolower((string) data_get($conteudo, 'publicacao.nome', ''));
                        $publicacaoCodigo = mb_strtolower((string) data_get($conteudo, 'publicacao.codigo', ''));

                        return ($publicacaoNome !== '' && mb_strpos($publicacaoNome, $normalizedSearch) !== false)
                            || ($publicacaoCodigo !== '' && mb_strpos($publicacaoCodigo, $normalizedSearch) !== false);
                    })->values();

                    $volume->setRelation('conteudos', $matchingConteudos);

                    return $matchingConteudos->isNotEmpty();
                })->values();

                $envio->setRelation('volumes', $filteredVolumes);

                return $envio;
            });
        }
        
        // Atribui os valores ao objeto para que a View e a Paginação funcionem corretamente
        $envios->searchFiltro = $search;
        $envios->perpage = $perpage;
        $envios->d1 = 3; // Para exibir 3 páginas antes da atual
        $envios->d2 = 3; // Para exibir 3 páginas depois da atual

        return view('envio-hierarchy.index', [
            'envios' => $envios,
            'search' => $search ?? '',
            'publicacoes' => Publicacao::where('congregacao_id', $congregacaoId)
                ->orderBy('nome')
                ->get(),
        ]);
    }

    /**
     * API endpoint para busca em tempo real (AJAX)
     */
    public function search(Request $request)
    {
        $search = $request->input('q', '');

        $congregacaoId = congregacaoAtivaId();

        $envios = Envio::where('congregacao_id', $congregacaoId)
            ->with([
                'volumes.conteudos.publicacao'
            ])
            ->orderByDesc('data')
            ->get();

        // Filtrar por busca
        if ($search) {
            $envios = $envios->filter(function ($envio) use ($search) {
                if (stripos($envio->nota, $search) !== false) {
                    return true;
                }
                
                foreach ($envio->volumes as $volume) {
                    if (stripos($volume->volume, $search) !== false) {
                        return true;
                    }
                    
                    foreach ($volume->conteudos as $conteudo) {
                        if (
                            stripos($conteudo->publicacao->nome, $search) !== false ||
                            stripos($conteudo->publicacao->codigo, $search) !== false
                        ) {
                            return true;
                        }
                    }
                }
                
                return false;
            });
        }

        return response()->json([
            'envios' => $envios->values(),
            'count' => $envios->count(),
        ]);
    }

    /**
     * Store a newly created Conteudo
     */
    public function storeConteudo(Request $request)
    {
        $congregacaoId = congregacaoAtivaId();

        $validated = $request->validate([
            'volume_id' => 'required|exists:volumes,id',
            'publicacao_id' => 'required|exists:publicacoes,id',
            'quantidade' => 'required|integer|min:1|max:9999',
        ]);

        // Verificar se o volume pertence a um envio da congregação ativa
        $volume = Volume::find($validated['volume_id']);
        if (!$volume) {
            return response()->json([
                'message' => 'Volume não encontrado',
            ], 404);
        }

        // Carregar a relação envio se não estiver carregada
        if (!$volume->relationLoaded('envio')) {
            $volume->load('envio');
        }

        if ($volume->envio->congregacao_id !== $congregacaoId) {
            return response()->json([
                'message' => 'Volume inválido para sua congregação',
            ], 403);
        }

        // Verifica se já existe este conteúdo no volume
        $existing = Conteudo::where('volume_id', $validated['volume_id'])
            ->where('publicacao_id', $validated['publicacao_id'])
            ->first();

        if ($existing) {
            return response()->json([
                'message' => 'Esta publicação já existe neste volume',
                'error' => true
            ], 409);
        }

        $conteudo = Conteudo::create($validated);
        $conteudo->load('publicacao');

        return response()->json([
            'message' => 'Conteúdo adicionado com sucesso',
            'conteudo' => $conteudo,
        ], 201);
    }

    /**
     * Update a Conteudo
     */
    public function updateConteudo(Request $request, Conteudo $conteudo)
    {
        $congregacaoId = congregacaoAtivaId();

        // Carregar a relação volume.envio se não estiver carregada
        if (!$conteudo->relationLoaded('volume')) {
            $conteudo->load('volume.envio');
        }

        // Verificar se o conteúdo pertence a um volume de um envio da congregação ativa
        if ($conteudo->volume->envio->congregacao_id !== $congregacaoId) {
            return response()->json([
                'message' => 'Sem permissão para atualizar este conteúdo',
            ], 403);
        }

        $validated = $request->validate([
            'quantidade' => 'required|integer|min:1|max:9999',
        ]);

        $conteudo->update($validated);
        $conteudo->load('publicacao');

        return response()->json([
            'message' => 'Conteúdo atualizado com sucesso',
            'conteudo' => $conteudo,
        ]);
    }

    /**
     * Delete a Conteudo
     */
    public function destroyConteudo(Conteudo $conteudo)
    {
        $congregacaoId = congregacaoAtivaId();

        // Carregar a relação volume.envio se não estiver carregada
        if (!$conteudo->relationLoaded('volume')) {
            $conteudo->load('volume.envio');
        }

        // Verificar se o conteúdo pertence a um volume de um envio da congregação ativa
        if ($conteudo->volume->envio->congregacao_id !== $congregacaoId) {
            return response()->json([
                'message' => 'Sem permissão para remover este conteúdo',
            ], 403);
        }

        $conteudo->delete();

        return response()->json([
            'message' => 'Conteúdo removido com sucesso',
        ]);
    }

    /**
     * Store a newly created Volume
     */
    public function storeVolume(Request $request)
    {
        $congregacaoId = congregacaoAtivaId();

        $validated = $request->validate([
            'envio_id' => 'required|exists:envios,id',
            'volume' => [
                'required',
                'regex:/^Volume ([1-9][0-9]?|100) de ([1-9][0-9]?|100) - Caixa ((?!0+$)\d{1,3})$/',
                function ($attribute, $value, $fail) {
                    if (preg_match('/^Volume (\d+) de (\d+) - Caixa \d+$/', $value, $matches)) {
                        $a = (int) $matches[1];
                        $b = (int) $matches[2];
                        if ($a > $b) {
                            $fail('O número do volume (A) não pode ser maior que o total de volumes (B).');
                        }
                    }
                },
            ],
        ]);

        // Verificar se o envio pertence à congregação ativa
        $envio = Envio::find($validated['envio_id']);
        if (!$envio || $envio->congregacao_id !== $congregacaoId) {
            return response()->json([
                'message' => 'Envio inválido para sua congregação',
            ], 403);
        }

        $volume = Volume::create($validated);
        $volume->load('conteudos.publicacao');

        return response()->json([
            'message' => 'Volume criado com sucesso',
            'volume' => $volume,
        ], 201);
    }

    /**
     * Update a Volume
     */
    public function updateVolume(Request $request, Volume $volume)
    {
        try {
            $congregacaoId = congregacaoAtivaId();

            // Carregar a relação envio se não estiver carregada
            if (!$volume->relationLoaded('envio')) {
                $volume->load('envio');
            }

            // Verificar se o volume existe e tem envio
            if (!$volume->envio) {
                return response()->json([
                    'message' => 'Volume não tem um envio associado',
                ], 404);
            }

            // Verificar se o volume pertence a um envio da congregação ativa
            if ($volume->envio->congregacao_id !== $congregacaoId) {
                return response()->json([
                    'message' => 'Sem permissão para atualizar este volume',
                ], 403);
            }

            $validated = $request->validate([
                'volume' => [
                    'required',
                    'regex:/^Volume ([1-9][0-9]?|100) de ([1-9][0-9]?|100) - Caixa ((?!0+$)\d{1,3})$/',
                    function ($attribute, $value, $fail) {
                        if (preg_match('/^Volume (\d+) de (\d+) - Caixa \d+$/', $value, $matches)) {
                            $a = (int) $matches[1];
                            $b = (int) $matches[2];
                            if ($a > $b) {
                                $fail('O número do volume (A) não pode ser maior que o total de volumes (B).');
                            }
                        }
                    },
                ],
            ]);

            $volume->update($validated);
            $volume->load('conteudos.publicacao');

            return response()->json([
                'message' => 'Volume atualizado com sucesso',
                'volume' => $volume,
            ]);
        } catch (\Exception $e) {
            \Log::error('Erro em updateVolume: ' . $e->getMessage(), [
                'exception' => $e,
                'volume_id' => $volume->id ?? null
            ]);
            
            return response()->json([
                'message' => 'Erro ao atualizar volume: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a Volume
     */
    public function destroyVolume(Volume $volume)
    {
        $congregacaoId = congregacaoAtivaId();

        // Carregar a relação envio se não estiver carregada
        if (!$volume->relationLoaded('envio')) {
            $volume->load('envio');
        }

        // Verificar se o volume pertence a um envio da congregação ativa
        if ($volume->envio->congregacao_id !== $congregacaoId) {
            return response()->json([
                'message' => 'Sem permissão para remover este volume',
            ], 403);
        }

        // Remove todos os conteúdos antes de deletar o volume
        $volume->conteudos()->delete();
        $volume->delete();

        return response()->json([
            'message' => 'Volume removido com sucesso',
        ]);
    }

    /**
     * Store a newly created Envio
     */
    public function storeEnvio(Request $request)
    {
        $congregacaoId = congregacaoAtivaId();

        $validated = $request->validate([
            'nota' => 'required|unique:envios,nota,NULL,id,congregacao_id,' . $congregacaoId . '|min:7|max:10',
            'data' => 'nullable|date',
            'retirada' => 'nullable|date',
            'inventariado' => 'nullable|boolean',
        ]);

        $validated['congregacao_id'] = $congregacaoId;
        $envio = Envio::create($validated);
        $envio->load('volumes.conteudos.publicacao');

        return response()->json([
            'message' => 'Envio criado com sucesso',
            'envio' => $envio,
        ], 201);
    }

    /**
     * Update an Envio
     */
    public function updateEnvio(Request $request, Envio $envio)
    {
        $congregacaoId = congregacaoAtivaId();

        // Verificar se o envio pertence à congregação ativa
        if ($envio->congregacao_id !== $congregacaoId) {
            return response()->json([
                'message' => 'Sem permissão para atualizar este envio',
            ], 403);
        }

        $validated = $request->validate([
            'nota' => [
                'required',
                'min:7',
                'max:10',
                Rule::unique('envios', 'nota')
                    ->ignore($envio->id)
                    ->where('congregacao_id', $congregacaoId),
            ],
            'data' => 'nullable|date',
            'retirada' => 'nullable|date',
            'inventariado' => 'nullable|boolean',
        ]);

        $envio->update($validated);
        $envio->load('volumes.conteudos.publicacao');

        return response()->json([
            'message' => 'Envio atualizado com sucesso',
            'envio' => $envio,
        ]);
    }

    /**
     * Delete an Envio
     */
    public function destroyEnvio(Envio $envio)
    {
        $congregacaoId = congregacaoAtivaId();

        // Verificar se o envio pertence à congregação ativa
        if ($envio->congregacao_id !== $congregacaoId) {
            return response()->json([
                'message' => 'Sem permissão para remover este envio',
            ], 403);
        }

        // Remove todos os volumes e conteúdos relacionados
        foreach ($envio->volumes as $volume) {
            $volume->conteudos()->delete();
        }
        $envio->volumes()->delete();
        $envio->delete();

        return response()->json([
            'message' => 'Envio removido com sucesso',
        ]);
    }

    /**
     * Store a newly created Publicacao (quick create for hierarchy)
     */
    public function storePublicacao(Request $request)
    {
        $congregacaoId = congregacaoAtivaId();

        $validated = $request->validate([
            'nome' => 'required|min:3|max:255|unique:publicacoes,nome,NULL,id,congregacao_id,' . $congregacaoId,
            'codigo' => 'required|min:2|max:10|unique:publicacoes,codigo,NULL,id,congregacao_id,' . $congregacaoId,
        ], [
            'nome.required' => 'O nome é obrigatório',
            'nome.unique' => 'Já existe uma publicação com este nome nesta congregação',
            'codigo.required' => 'O código é obrigatório',
            'codigo.unique' => 'Já existe uma publicação com este código nesta congregação',
        ]);

        $validated['congregacao_id'] = $congregacaoId;
        
        $publicacao = Publicacao::create($validated);

        return response()->json([
            'message' => 'Publicação criada com sucesso',
            'publicacao' => $publicacao,
        ], 201);
    }
}
