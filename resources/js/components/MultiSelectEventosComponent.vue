<template>
    <div class="multiselect-container">
        <div class="dropdown" ref="dropdown">
            <button class="btn btn-sm btn-outline-secondary dropdown-toggle form-control text-start" type="button" @click="toggleDropdown">
                {{ selectedOptionsText || placeholder }}
            </button>
            <div class="dropdown-menu p-2" :class="{ 'show': isOpen }" @click.stop>
                <div class="form-check" v-for="option in options" :key="option.value">
                    <input class="form-check-input" type="checkbox" :value="option.value" :id="`evento-${option.value}`" v-model="selectedValues">
                    <label class="form-check-label" :for="`evento-${option.value}`">
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
        options: { type: Array, required: true },
        old_ids: { type: [Array, String], default: () => [] },
        name: { type: String, default: 'eventos[]' },
        placeholder: { type: String, default: 'Selecionar Eventos...' },
    },
    data() {
        return {
            isOpen: false,
            selectedValues: [],
        };
    },
    computed: {
        selectedOptionsText() {
            if (this.selectedValues.length === 0) {
                return '';
            }
            if (this.selectedValues.length === 1) {
                const selected = this.options.find(opt => opt.value === this.selectedValues[0]);
                return selected ? selected.text : '';
            }
            return `${this.selectedValues.length} selecionados`;
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
                values = this.old_ids;
            } else if (typeof this.old_ids === 'string' && this.old_ids) {
                try {
                    const parsed = JSON.parse(this.old_ids);
                    if (Array.isArray(parsed)) {
                        values = parsed;
                    }
                } catch (e) {
                    console.error("Erro ao parsear old_ids: ", e);
                }
            } else if (this.old_ids && !Array.isArray(this.old_ids)) {
                values = [this.old_ids];
            }
            
            // Filtrar apenas valores válidos
            this.selectedValues = values.filter(val => {
                return val !== undefined && 
                       val !== null && 
                       val !== '' && 
                       val !== '[]';
            });
        }
    },
    watch: {
        old_ids: {
            immediate: true,
            handler(newVal) {
                this.initializeSelectedValues();
            }
        }
    },
    mounted() {
        document.addEventListener('click', this.closeDropdown);
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
    max-height: 200px;
    overflow-y: auto;
    width: 100%;
    left: 0;
    right: auto;
}

.btn.dropdown-toggle {
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.btn.dropdown-toggle::after {
    margin-left: auto;
}

.form-check {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
</style>
