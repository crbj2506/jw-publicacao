<template>
        <!-- O container do input-group -->
        <div class="input-group has-validation">
            <!-- O label que vem da prop 'label' -->
            <span class="input-group-text">{{label}}</span>

            <!-- 
                Renderiza um input padrão (sem máscara) se a prop 'mascara' NÃO for fornecida.
                Isso é crucial para evitar que a diretiva v-maska interfira com inputs numéricos
                ou de outros tipos que não necessitam de formatação (ex: problema da vírgula).
            -->
            <input 
                v-if="!mascara"
                class="form-control" 
                v-bind="commonAttrs"
                @change="type === 'file' ? validateFileSize($event) : null"
                >

            <!-- 
                Renderiza um input COM a diretiva v-maska se a prop 'mascara' for fornecida.
                Isso aplica a formatação de máscara apenas quando explicitamente solicitada.
            -->
            <input 
                v-else
                class="form-control" 
                v-bind="commonAttrs"
                @change="type === 'file' ? validateFileSize($event) : null"
                v-maska="mascara"
                >

            <!-- Exibe a mensagem de erro de validação, se houver -->
            <div class="invalid-feedback text-start">{{ message }}</div>
        </div>
        
        <!-- Exibe uma imagem de preview se a prop 'filename' for fornecida (usado para uploads de imagem) -->
        <img v-if="filename" :src="'/storage/'+filename"  class="img-thumbnail">
</template>

<script>
    import { vMaska } from "maska"

    export default {
        // Desativa a herança automática de atributos.
        // Isso evita que atributos passados para o componente (como 'inputmode')
        // sejam aplicados no elemento raiz (o div), o que causaria um aviso no Vue,
        // já que o componente tem múltiplos elementos no nível raiz (fragmento).
        inheritAttrs: false,
        
        // Define todas as propriedades que o componente aceita
        props: [ 
            'class',
            'disabled',
            'id',
            'filename',
            'label',
            'mascara',
            'maxlength',
            'message',
            'name',
            'placeholder', 
            'required',
            'step',
            'style',
            'type',
            'value',
        ],

        // Define as diretivas usadas pelo componente (neste caso, apenas v-maska)
        directives: { 
            maska: vMaska 
        },

        // Propriedades computadas para otimizar o template
        computed: {
            // Agrupa todos os atributos comuns em um único objeto para evitar repetição no template.
            // Isso torna o template mais limpo e fácil de manter.
            commonAttrs() {
                return {
                    // Props explícitas que são passadas para o input
                    class: this.class,
                    disabled: this.disabled,
                    id: this.id,
                    filename: this.filename,
                    maxlength: this.maxlength,
                    name: this.name,
                    placeholder: this.placeholder,
                    type: this.type,
                    step: this.step,
                    value: this.value,
                    required: this.required,
                    
                    // O operador 'spread' (...) aplica todos os outros atributos
                    // que foram passados para o componente mas não são props (ex: 'inputmode').
                    // Isso é possível graças ao 'inheritAttrs: false'.
                    ...this.$attrs
                }
            }
        },
        methods: {
            validateFileSize(event) {
                const file = event.target.files[0];
                if (!file) return;

                const maxSizeKB = 512; // 512 KB
                if ((file.size / 1024) > maxSizeKB) {
                    alert(`Arquivo muito grande! O tamanho máximo permitido é de ${maxSizeKB} KB. Seu arquivo tem ${Math.round(file.size / 1024)} KB.`);
                    // Limpa o valor do input para evitar que o arquivo inválido seja enviado
                    event.target.value = '';
                }
            }
        }
    }
</script>