<template>
    <div class="multiselect-container">
        <div class="dropdown" ref="dropdown">
            <button class="btn btn-sm btn-outline-secondary dropdown-toggle form-control text-start" type="button" @click="toggleDropdown">
                {{ selectedOptionsText || placeholder }}
            </button>
            <div class="dropdown-menu p-2" :class="{ 'show': isOpen }" @click.stop>
                <input type="text" class="form-control mb-2" v-model="searchTerm" :placeholder="searchPlaceholder">
                <div class="form-check" v-for="option in filteredOptions" :key="option.value">
                    <input class="form-check-input" type="checkbox" :value="option.value" :id="`cong-${option.value}`" v-model="selectedValues">
                    <label class="form-check-label" :for="`cong-${option.value}`">
                        {{ option.text }}
                    </label>
                </div>
            </div>
        </div>
        <!-- Enviar múltiplos inputs para Laravel processar como array -->
        <input v-for="value in selectedValues" :key="value" type="hidden" :name="name" :value="value">
    </div>
</template>

<script>
export default {
    props: {
        options: { type: Array, required: true }, // Array de { value: id, text: nome }
        old_ids: { type: [Array, String], default: () => [] }, // Pode ser um array ou string JSON
        name: { type: String, default: 'congregacao_ids[]' },
        placeholder: { type: String, default: 'Selecionar Congregações...' },
        searchPlaceholder: { type: String, default: 'Buscar congregação...' },
    },
    data() {
        return {
            isOpen: false,
            searchTerm: '',
            selectedValues: [],
        };
    },
    computed: {
        parsedOptions() {
            return this.options.map(option => ({ 
                value: String(option.value), 
                text: option.text 
            }));
        },
        filteredOptions() {
            if (!this.searchTerm) {
                return this.parsedOptions;
            }
            const term = this.searchTerm.toLowerCase();
            return this.parsedOptions.filter(option =>
                option.text.toLowerCase().includes(term)
            );
        },
        selectedOptionsText() {
            if (this.selectedValues.length === 0) {
                return '';
            }
            if (this.selectedValues.length === 1) {
                const selected = this.parsedOptions.find(opt => opt.value === this.selectedValues[0]);
                return selected ? selected.text : '';
            }
            return `${this.selectedValues.length} selecionadas`;
        }
    },
    methods: {
        toggleDropdown() {
            this.isOpen = !this.isOpen;
        },
        closeDropdown(event) {
            if (!this.$refs.dropdown.contains(event.target)) {
                this.isOpen = false;
            }
        },
        initializeSelectedValues() {
            let values = [];
            
            if (Array.isArray(this.old_ids)) {
                values = this.old_ids.map(String);
            } else if (typeof this.old_ids === 'string' && this.old_ids) {
                try {
                    const parsed = JSON.parse(this.old_ids);
                    if (Array.isArray(parsed)) {
                        values = parsed.map(String);
                    }
                } catch (e) {
                    console.error("Erro ao parsear old_ids: ", e);
                }
            } else if (this.old_ids && !Array.isArray(this.old_ids)) {
                // Caso old_ids seja um único ID não em array, converte para array de string
                values = [String(this.old_ids)];
            }
            
            // Filtrar apenas valores válidos (numéricos e não vazios)
            this.selectedValues = values.filter(val => {
                return val !== undefined && 
                       val !== null && 
                       val !== '' && 
                       val !== '[]' &&
                       !isNaN(val);
            });
        }
    },
    watch: {
        old_ids: { // Monitora mudanças em old_ids para re-inicializar selectedValues
            immediate: true, // Executa imediatamente na criação do componente
            handler(newVal) {
                this.initializeSelectedValues();
            }
        }
    },
    mounted() {
        document.addEventListener('click', this.closeDropdown);
        // A inicialização agora é feita feita pelo watcher com immediate: true
    },
    beforeUnmount() {
        document.removeEventListener('click', this.closeDropdown);
    }
};
</script>

<style scoped>
.multiselect-container {
    position: relative;
}

.dropdown-menu {
    max-height: 200px; /* Altura máxima para a lista de opções */
    overflow-y: auto;
    width: 100%; /* Garante que o menu ocupe toda a largura */
    left: 0; /* Alinha o dropdown com o botão */
    right: auto;
}

.btn.dropdown-toggle {
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.btn.dropdown-toggle::after {
    margin-left: auto; /* Empurra a seta para a direita */
}

/* Estilo para evitar que a lista de checkbox ocupe mais de uma linha */
.form-check {
    white-space: nowrap; /* Impede que o texto quebre para a próxima linha */
    overflow: hidden;
    text-overflow: ellipsis;
}

</style>
