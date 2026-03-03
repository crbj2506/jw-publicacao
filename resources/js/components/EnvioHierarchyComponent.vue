<template>
  <div class="envio-hierarchy">
    <!-- Accordion -->
    <div class="accordion" id="enviosAccordion">
      <div v-if="enviosData.length === 0" class="alert alert-info m-3">
        Nenhum envio cadastrado
      </div>

      <div v-for="envio in enviosData" :key="envio.id" class="accordion-item">
        <!-- Envio Header -->
        <h2 class="accordion-header d-flex align-items-stretch">
          <button
            class="accordion-button collapsed flex-grow-1 py-2"
            type="button"
            :data-bs-target="`#envio-${envio.id}`"
            data-bs-toggle="collapse"
          >
            <strong>{{ envio.nota }}</strong>
            <span v-if="envio.data" class="badge bg-primary ms-2">
              📦 {{ formatDate(envio.data) }}
            </span>
            <span class="badge bg-info ms-2">{{ envio.volumes.length }} vol.</span>
            <span v-if="envio.retirada" class="badge bg-secondary ms-2">
              📥 {{ formatDate(envio.retirada) }}
            </span>
            <span 
              :class="envio.inventariado ? 'badge bg-success' : 'badge bg-warning'"
              class="ms-2"
            >
              {{ envio.inventariado ? '✅ Inventariado' : '⏳ Pendente' }}
            </span>
            <small class="ms-auto text-muted">{{ envio.congregacao?.nome }}</small>
          </button>
          <div class="d-flex align-items-center gap-1 px-2">
            <button
              v-if="canManage"
              @click.stop="showEditEnvioModal(envio)"
              class="btn btn-sm btn-outline-warning"
              type="button"
              title="Editar"
            >
              ✎
            </button>
            <button
              v-if="canManage"
              @click.stop="deleteEnvio(envio.id)"
              class="btn btn-sm btn-outline-danger"
              type="button"
              title="Excluir"
            >
              🗑️
            </button>
          </div>
        </h2>

        <!-- Envio Body (Volumes) -->
        <div :id="`envio-${envio.id}`" class="accordion-collapse collapse" data-bs-parent="#enviosAccordion">
          <div class="accordion-body p-2">
            <!-- Sub-accordion Volumes -->
            <div class="accordion accordion-flush" :id="`volumesAccordion-${envio.id}`">
              <div v-for="volume in envio.volumes" :key="volume.id" class="accordion-item">
                <!-- Volume Header -->
                <h3 class="accordion-header d-flex align-items-stretch">
                  <button
                    class="accordion-button collapsed flex-grow-1 py-2"
                    type="button"
                    :data-bs-target="`#volume-${volume.id}`"
                    data-bs-toggle="collapse"
                    style="padding-left: 2rem"
                  >
                    {{ volume.volume }}
                    <span class="badge bg-secondary ms-2">{{ volume.conteudos.length }} itens</span>
                  </button>
                  <div class="d-flex align-items-center gap-1 px-2">
                    <button
                      v-if="canManage"
                      @click.stop="showNovoConteudoModal(volume.id)"
                      class="btn btn-sm btn-outline-primary"
                      type="button"
                      title="Adicionar Item"
                    >
                      +
                    </button>
                    <button
                      v-if="canManage"
                      @click.stop="showEditVolumeModal(volume)"
                      class="btn btn-sm btn-outline-warning"
                      type="button"
                      title="Editar"
                    >
                      ✎
                    </button>
                    <button
                      v-if="canManage"
                      @click.stop="deleteVolume(volume.id)"
                      class="btn btn-sm btn-outline-danger"
                      type="button"
                      title="Excluir"
                    >
                      🗑️
                    </button>
                  </div>
                </h3>

                <!-- Volume Body (Conteúdos) -->
                <div :id="`volume-${volume.id}`" class="accordion-collapse collapse">
                  <div class="accordion-body p-0">
                    <table class="table table-sm table-hover mb-0">
                      <tbody>
                        <tr v-if="volume.conteudos.length === 0">
                          <td colspan="4" class="text-muted text-center py-3">
                            Nenhuma publicação neste volume
                          </td>
                        </tr>
                        <tr v-for="conteudo in volume.conteudos" :key="conteudo.id">
                          <td class="align-middle conteudo-indent">
                            {{ conteudo.publicacao?.nome }} 
                            <span class="text-muted">({{ conteudo.publicacao?.codigo }})</span>
                          </td>
                          <td class="text-center align-middle" style="width: 120px">
                            <input
                              type="number"
                              class="form-control form-control-sm"
                              :value="conteudo.quantidade"
                              :disabled="!canManage"
                              @change="(e) => updateConteudo(conteudo.id, parseInt(e.target.value))"
                              min="1"
                              max="9999"
                            />
                          </td>
                          <td v-if="canManage" class="text-center align-middle" style="width: 50px">
                            <button
                              @click.stop="deleteConteudo(conteudo.id)"
                              class="btn btn-sm btn-outline-danger"
                              type="button"
                            >
                              🗑️
                            </button>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>

            <!-- Botão Novo Volume -->
            <div class="mt-2">
              <button
                v-if="canManage"
                @click="showNovoVolumeModal(envio.id)"
                class="btn btn-sm btn-outline-success w-100"
                type="button"
              >
                + Novo Volume
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- MODAIS -->

    <!-- Modal Novo/Editar Envio -->
    <div class="modal fade" :id="modalEnvioId" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              {{ editingEnvio ? "Editar Envio" : "Novo Envio" }}
            </h5>
            <button type="button" class="btn-close" :disabled="loadingModal" @click="closeModalEnvio"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Nota <span class="text-danger">*</span></label>
              <input
                v-model="formEnvio.nota"
                type="text"
                class="form-control"
                :class="{ 'is-invalid': errors.nota }"
                placeholder="Ex: P001"
                minlength="7"
                maxlength="10"
              />
              <div v-if="errors.nota" class="invalid-feedback">{{ errors.nota }}</div>
            </div>

            <div class="mb-3">
              <label class="form-label">Data</label>
              <input v-model="formEnvio.data" type="date" class="form-control" />
            </div>

            <div class="mb-3">
              <label class="form-label">Retirada</label>
              <input v-model="formEnvio.retirada" type="date" class="form-control" />
            </div>

            <div class="form-check">
              <input 
                v-model="formEnvio.inventariado" 
                type="checkbox" 
                class="form-check-input" 
                :id="`inventariado-${editingEnvio?.id || 'new'}`"
              />
              <label class="form-check-label" :for="`inventariado-${editingEnvio?.id || 'new'}`">
                ✅ Inventariado
              </label>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="closeModalEnvio" :disabled="loadingModal">
              Cancelar
            </button>
            <button
              type="button"
              class="btn btn-primary"
              @click="saveEnvio"
              :disabled="loadingModal"
            >
              {{ loadingModal ? "Salvando..." : "Salvar" }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Novo/Editar Volume -->
    <div class="modal fade" :id="modalVolumeId" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              {{ editingVolume ? "Editar Volume" : "Novo Volume" }}
            </h5>
            <button type="button" class="btn-close" :disabled="loadingModal" @click="closeModalVolume"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Volume <span class="text-danger">*</span></label>
              <input
                v-model="formVolume.volume"
                type="text"
                class="form-control"
                :class="{ 'is-invalid': errors.volume }"
                placeholder="Ex: Volume 1 de 10 - Caixa 5"
              />
              <small class="form-text text-muted">
                Formato: "Volume A de B - Caixa C" (A, B até 100, C até 999)
              </small>
              <div v-if="errors.volume" class="invalid-feedback">{{ errors.volume }}</div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="closeModalVolume" :disabled="loadingModal">
              Cancelar
            </button>
            <button
              type="button"
              class="btn btn-primary"
              @click="saveVolume"
              :disabled="loadingModal"
            >
              {{ loadingModal ? "Salvando..." : "Salvar" }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Novo Conteúdo -->
    <div class="modal fade" :id="modalConteudoId" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Adicionar Publicação</h5>
            <button type="button" class="btn-close" :disabled="loadingModal" @click="closeModalConteudo"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Publicação <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text">🔍</span>
                <input
                  v-model="filtroPublicacao"
                  type="text"
                  class="form-control"
                  placeholder="Filtrar por nome ou código..."
                />
              </div>
              <select
                v-model="formConteudo.publicacao_id"
                class="form-select mt-2"
                :class="{ 'is-invalid': errors.publicacao_id }"
              >
                <option value="">Selecione...</option>
                <option v-for="pub in publicacoesFiltered" :key="pub.id" :value="pub.id">
                  {{ pub.nome }} ({{ pub.codigo }})
                </option>
              </select>
              <small v-if="publicacoesFiltered.length === 0" class="text-muted d-block mt-2">
                Nenhuma publicação encontrada
              </small>
              <button
                @click.stop="showNovaPublicacaoModal"
                type="button"
                class="btn btn-sm btn-outline-success mt-2 w-100"
              >
                + Nova Publicação
              </button>
              <div v-if="errors.publicacao_id" class="invalid-feedback">{{ errors.publicacao_id }}</div>
            </div>

            <div class="mb-3">
              <label class="form-label">Quantidade <span class="text-danger">*</span></label>
              <input
                v-model="formConteudo.quantidade"
                type="number"
                class="form-control"
                :class="{ 'is-invalid': errors.quantidade }"
                min="1"
                max="9999"
              />
              <div v-if="errors.quantidade" class="invalid-feedback">{{ errors.quantidade }}</div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="closeModalConteudo" :disabled="loadingModal">
              Cancelar
            </button>
            <button
              type="button"
              class="btn btn-primary"
              @click="saveConteudo"
              :disabled="loadingModal"
            >
              {{ loadingModal ? "Salvando..." : "Adicionar" }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Nova Publicação -->
    <div class="modal fade" :id="modalPublicacaoId" tabindex="-1">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Nova Publicação</h5>
            <button type="button" class="btn-close" :disabled="loadingModal" @click="closeModalPublicacao"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Nome <span class="text-danger">*</span></label>
              <input
                v-model="formPublicacao.nome"
                type="text"
                class="form-control"
                :class="{ 'is-invalid': errorsPublicacao.nome }"
                placeholder="Ex: Revista Atalaia"
              />
              <div v-if="errorsPublicacao.nome" class="invalid-feedback">{{ errorsPublicacao.nome }}</div>
            </div>

            <div class="mb-3">
              <label class="form-label">Código <span class="text-danger">*</span></label>
              <input
                v-model="formPublicacao.codigo"
                type="text"
                class="form-control"
                :class="{ 'is-invalid': errorsPublicacao.codigo }"
                placeholder="Ex: ATL2426"
              />
              <div v-if="errorsPublicacao.codigo" class="invalid-feedback">{{ errorsPublicacao.codigo }}</div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="closeModalPublicacao" :disabled="loadingModal">
              Cancelar
            </button>
            <button
              type="button"
              class="btn btn-success"
              @click="savePublicacao"
              :disabled="loadingModal"
            >
              {{ loadingModal ? "Criando..." : "Criar" }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "EnvioHierarchyComponent",
  props: {
    enviosInitial: {
      type: Array,
      required: true,
    },
    publicacoes: {
      type: Array,
      required: true,
    },
    canManage: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      loadingModal: false,
      enviosData: [],
      editingEnvio: null,
      editingVolume: null,
      filtroPublicacao: "",
      formEnvio: {
        nota: "",
        data: "",
        retirada: "",
      },
      formVolume: {
        volume: "",
        envio_id: null,
      },
      formConteudo: {
        volume_id: null,
        publicacao_id: "",
        quantidade: 1,
      },
      errors: {},
      modalEnvioId: "modalEnvio",
      modalVolumeId: "modalVolume",
      modalConteudoId: "modalConteudo",
      modalPublicacaoId: "modalPublicacao",
      formPublicacao: {
        nome: "",
        codigo: "",
      },
      errorsPublicacao: {},
    };
  },
  computed: {
    modalEnvio() {
      return new (window.bootstrap || {}).Modal(document.getElementById(this.modalEnvioId) || {});
    },
    modalVolume() {
      return new (window.bootstrap || {}).Modal(document.getElementById(this.modalVolumeId) || {});
    },
    modalConteudo() {
      return new (window.bootstrap || {}).Modal(document.getElementById(this.modalConteudoId) || {});
    },
    modalPublicacao() {
      return new (window.bootstrap || {}).Modal(document.getElementById(this.modalPublicacaoId) || {});
    },
    publicacoesFiltered() {
      if (!this.filtroPublicacao.trim()) {
        return this.publicacoes;
      }
      const filtro = this.filtroPublicacao.toLowerCase();
      return this.publicacoes.filter(
        (pub) =>
          pub.nome.toLowerCase().includes(filtro) ||
          pub.codigo.toLowerCase().includes(filtro)
      );
    },
  },
  mounted() {
    this.enviosData = this.enviosInitial;
    
    // Ordena por data desc, depois por nota desc
    this.enviosData.sort((a, b) => {
      const dataA = a.data ? new Date(a.data).getTime() : 0;
      const dataB = b.data ? new Date(b.data).getTime() : 0;
      if (dataB !== dataA) return dataB - dataA;

      const notaA = parseInt(a.nota) || 0;
      const notaB = parseInt(b.nota) || 0;
      return notaB - notaA;
    });
    // Conectar botão externo ao método showNovoEnvioModal
    const btnNovoEnvio = document.getElementById('btnNovoEnvio');
    if (btnNovoEnvio) {
      btnNovoEnvio.addEventListener('click', () => {
        this.showNovoEnvioModal();
      });
    }
  },
  methods: {
    formatDate(date) {
      if (!date) return "";
      return new Date(date).toLocaleDateString("pt-BR");
    },

    // ENVIO OPERATIONS
    showNovoEnvioModal() {
      if (!this.canManage) return;
      this.editingEnvio = null;
      this.formEnvio = { nota: "", data: "", retirada: "", inventariado: false };
      this.errors = {};
      this.$nextTick(() => {
        new (window.bootstrap || {}).Modal(document.getElementById(this.modalEnvioId)).show();
      });
    },

    showEditEnvioModal(envio) {
      if (!this.canManage) return;
      this.editingEnvio = envio;
      // Formatar as datas para o formato YYYY-MM-DD esperado pelo input type="date"
      this.formEnvio = { 
        nota: envio.nota,
        data: envio.data ? envio.data.split('T')[0] : "",
        retirada: envio.retirada ? envio.retirada.split('T')[0] : "",
        inventariado: envio.inventariado || false
      };
      this.errors = {};
      this.$nextTick(() => {
        new (window.bootstrap || {}).Modal(document.getElementById(this.modalEnvioId)).show();
      });
    },

    closeModalEnvio() {
      const modal = window.bootstrap?.Modal.getInstance(document.getElementById(this.modalEnvioId));
      modal?.hide();
    },

    saveEnvio() {
      if (!this.canManage) return;
      this.loadingModal = true;
      this.errors = {};

      const url = this.editingEnvio
        ? `/api/envio/envio/${this.editingEnvio.id}`
        : "/api/envio/envio";

      const method = this.editingEnvio ? "put" : "post";

      // Preparar dados: converter strings vazias em null para campos nullable
      const data = {
        nota: this.formEnvio.nota,
        data: this.formEnvio.data || null,
        retirada: this.formEnvio.retirada || null,
        inventariado: this.formEnvio.inventariado ? 1 : 0,
      };

      axios({
        method,
        url,
        data,
      })
        .then((response) => {
          if (this.editingEnvio) {
            const index = this.enviosData.findIndex((e) => e.id === this.editingEnvio.id);
            if (index !== -1) {
              this.enviosData[index] = response.data.envio;
            }
          } else {
            this.enviosData.unshift(response.data.envio);
          }
          // Re-ordena após adicionar/editar
          this.enviosData.sort((a, b) => {
            const dataA = a.data ? new Date(a.data).getTime() : 0;
            const dataB = b.data ? new Date(b.data).getTime() : 0;
            if (dataB !== dataA) return dataB - dataA;

            const notaA = parseInt(a.nota) || 0;
            const notaB = parseInt(b.nota) || 0;
            return notaB - notaA;
          });
          this.closeModalEnvio();
        })
        .catch((error) => {
          if (error.response?.status === 422) {
            this.errors = error.response.data.errors || {};
          } else if (error.response?.status === 403) {
            alert(error.response.data.message || "Sem permissão para realizar esta ação");
          } else {
            alert(`Erro ao salvar envio (${error.response?.status}): ${error.response?.data?.message || error.message}`);
          }
        })
        .finally(() => {
          this.loadingModal = false;
        });
    },

    deleteEnvio(id) {
      if (!this.canManage) return;
      if (!confirm("Deseja remover este envio e todos seus volumes/conteúdos?")) return;

      axios
        .delete(`/api/envio/envio/${id}`)
        .then(() => {
          this.enviosData = this.enviosData.filter((e) => e.id !== id);
        })
        .catch((error) => {
          if (error.response?.status === 403) {
            alert(error.response.data.message || "Sem permissão para remover este envio");
          } else {
            alert("Erro ao remover envio");
          }
        });
    },

    // VOLUME OPERATIONS
    showNovoVolumeModal(envioId) {
      if (!this.canManage) return;
      this.formVolume = { volume: "", envio_id: envioId };
      this.errors = {};
      this.$nextTick(() => {
        new (window.bootstrap || {}).Modal(document.getElementById(this.modalVolumeId)).show();
      });
    },

    showEditVolumeModal(volume) {
      if (!this.canManage) return;
      this.editingVolume = volume;
      // Enviar apenas os campos necessários
      this.formVolume = { 
        volume: volume.volume,
        envio_id: volume.envio_id 
      };
      this.errors = {};
      this.$nextTick(() => {
        new (window.bootstrap || {}).Modal(document.getElementById(this.modalVolumeId)).show();
      });
    },

    closeModalVolume() {
      const modal = window.bootstrap?.Modal.getInstance(document.getElementById(this.modalVolumeId));
      modal?.hide();
    },

    saveVolume() {
      if (!this.canManage) return;
      this.loadingModal = true;
      this.errors = {};

      const url = this.editingVolume
        ? `/api/envio/volume/${this.editingVolume.id}`
        : "/api/envio/volume";

      const method = this.editingVolume ? "put" : "post";

      // Preparar dados - enviar apenas os campos necessários
      const data = {
        volume: this.formVolume.volume,
      };

      // Se for criar, incluir envio_id
      if (!this.editingVolume) {
        data.envio_id = this.formVolume.envio_id;
      }

      axios({
        method,
        url,
        data,
      })
        .then((response) => {
          const envio = this.enviosData.find((e) => e.id === this.formVolume.envio_id);
          if (envio) {
            if (this.editingVolume) {
              const index = envio.volumes.findIndex((v) => v.id === this.editingVolume.id);
              if (index !== -1) {
                envio.volumes[index] = response.data.volume;
              }
            } else {
              envio.volumes.push(response.data.volume);
            }
          }
          this.closeModalVolume();
        })
        .catch((error) => {
          if (error.response?.status === 422) {
            this.errors = error.response.data.errors || {};
          } else if (error.response?.status === 403) {
            alert(error.response.data.message || "Sem permissão para realizar esta ação");
          } else {
            alert(`Erro ao salvar volume (${error.response?.status}): ${error.response?.data?.message || error.message}`);
          }
        })
        .finally(() => {
          this.loadingModal = false;
        });
    },

    deleteVolume(id) {
      if (!this.canManage) return;
      if (!confirm("Deseja remover este volume e todo seu conteúdo?")) return;

      axios
        .delete(`/api/envio/volume/${id}`)
        .then(() => {
          this.enviosData.forEach((envio) => {
            envio.volumes = envio.volumes.filter((v) => v.id !== id);
          });
        })
        .catch((error) => {
          if (error.response?.status === 403) {
            alert(error.response.data.message || "Sem permissão para remover este volume");
          } else {
            alert("Erro ao remover volume");
          }
        });
    },

    // CONTEUDO OPERATIONS
    showNovoConteudoModal(volumeId) {
      if (!this.canManage) return;
      this.formConteudo = { volume_id: volumeId, publicacao_id: "", quantidade: 1 };
      this.filtroPublicacao = "";
      this.errors = {};
      this.$nextTick(() => {
        new (window.bootstrap || {}).Modal(document.getElementById(this.modalConteudoId)).show();
      });
    },

    closeModalConteudo() {
      const modal = window.bootstrap?.Modal.getInstance(document.getElementById(this.modalConteudoId));
      modal?.hide();
    },

    saveConteudo() {
      if (!this.canManage) return;
      this.loadingModal = true;
      this.errors = {};

      axios
        .post("/api/envio/conteudo", this.formConteudo)
        .then((response) => {
          const volume = this.findVolume(this.formConteudo.volume_id);
          if (volume) {
            volume.conteudos.push(response.data.conteudo);
          }
          this.closeModalConteudo();
        })
        .catch((error) => {
          if (error.response?.status === 422) {
            this.errors = error.response.data.errors || {};
          } else if (error.response?.status === 409) {
            alert(error.response.data.message || "Erro ao adicionar");
          } else if (error.response?.status === 403) {
            alert(error.response.data.message || "Sem permissão para realizar esta ação");
          } else {
            alert("Erro ao adicionar conteúdo");
          }
        })
        .finally(() => {
          this.loadingModal = false;
        });
    },

    updateConteudo(id, quantidade) {
      if (!this.canManage) return;
      axios
        .put(`/api/envio/conteudo/${id}`, { quantidade })
        .catch((error) => {
          if (error.response?.status === 403) {
            alert(error.response.data.message || "Sem permissão para realizar esta ação");
          } else {
            alert("Erro ao atualizar quantidade");
          }
        });
    },

    deleteConteudo(id) {
      if (!this.canManage) return;
      if (!confirm("Deseja remover esta publicação?")) return;

      axios
        .delete(`/api/envio/conteudo/${id}`)
        .then(() => {
          this.enviosData.forEach((envio) => {
            envio.volumes.forEach((volume) => {
              volume.conteudos = volume.conteudos.filter((c) => c.id !== id);
            });
          });
        })
        .catch((error) => {
          if (error.response?.status === 403) {
            alert(error.response.data.message || "Sem permissão para remover esta publicação");
          } else {
            alert("Erro ao remover publicação");
          }
        });
    },

    // PUBLICACAO OPERATIONS
    showNovaPublicacaoModal() {
      if (!this.canManage) return;
      this.formPublicacao = { nome: "", codigo: "" };
      this.errorsPublicacao = {};
      this.$nextTick(() => {
        new (window.bootstrap || {}).Modal(document.getElementById(this.modalPublicacaoId)).show();
      });
    },

    closeModalPublicacao() {
      const modal = window.bootstrap?.Modal.getInstance(document.getElementById(this.modalPublicacaoId));
      modal?.hide();
    },

    savePublicacao() {
      if (!this.canManage) return;
      this.loadingModal = true;
      this.errorsPublicacao = {};

      axios
        .post("/api/envio/publicacao", this.formPublicacao)
        .then((response) => {
          // Adicionar a nova publicação ao array
          this.publicacoes.push(response.data.publicacao);
          
          // Selecionar automaticamente a nova publicação
          this.formPublicacao = { nome: "", codigo: "" };
          this.filtroPublicacao = "";
          
          this.closeModalPublicacao();
          
          // Volta o foco para o modal de conteúdo
          this.$nextTick(() => {
            new (window.bootstrap || {}).Modal(document.getElementById(this.modalConteudoId)).show();
          });
        })
        .catch((error) => {
          if (error.response?.status === 422) {
            this.errorsPublicacao = error.response.data.errors || {};
          } else {
            const errorMsg = error.response?.data?.message || error.message || "Erro ao criar publicação";
            alert(errorMsg);
            console.error("Erro completo:", error.response || error);
          }
        })
        .finally(() => {
          this.loadingModal = false;
        });
    },

    findVolume(volumeId) {
      for (const envio of this.enviosData) {
        const volume = envio.volumes.find((v) => v.id === volumeId);
        if (volume) return volume;
      }
      return null;
    },
  },
};
</script>

<style scoped>
.envio-hierarchy {
  padding: 1rem 0;
}

.accordion-button:not(.collapsed),
.accordion-button:focus {
  background-color: #e7f3ff;
  color: #0c63e4;
}

.accordion-header {
  margin-bottom: 0 !important;
}

.accordion-header.d-flex {
  height: auto;
}

.accordion-header.d-flex .btn {
  border-radius: 0;
  padding: 0.25rem 0.5rem;
  font-size: 0.85rem;
}

.accordion-header.d-flex .accordion-button {
  border-radius: 0;
  padding: 0.35rem 0.75rem;
  font-size: 0.9rem;
}

.accordion-header.d-flex .accordion-button.py-2 {
  padding-top: 0.35rem !important;
  padding-bottom: 0.35rem !important;
}

.accordion-item:first-child .accordion-header.d-flex .accordion-button {
  border-top-left-radius: 0.25rem;
}

.accordion-item:first-child .accordion-header.d-flex .btn:last-child {
  border-top-right-radius: 0.25rem;
}

.accordion-flush > .accordion-item > .accordion-header.d-flex .accordion-button {
  padding-top: 0.3rem;
  padding-bottom: 0.3rem;
}

.accordion-flush > .accordion-item > .accordion-body {
  padding: 0;
}

.conteudo-indent {
  padding-left: 3rem;
}

.badge {
  white-space: nowrap;
}

table tbody tr:hover {
  background-color: #f5f5f5;
}

.form-control:focus,
.form-select:focus {
  border-color: #80bdff;
  box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}
</style>
