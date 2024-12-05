<template>
    <div class="input-group has-validation">
        <span class="input-group-text">Quantidade</span>
        <input 
            class="form-control"
            :disabled="disabled"
            type="number"
            v-model.number="quantidade_int" 
            @input="updateCentimetros"
            min="0" step="1"
            >
        <span class="input-group-text">Centímetros</span>
        <input 
            class="form-control"
            :disabled="disabled"
            type="number"
            v-model.number="centimetros" 
            @input="updateQuantidade"
            min="0" step="0.1"
            >         
    </div>
  </template>
  
  <script>
  export default {
        props: [ 
            'centimetros',
            'disabled',
            'quantidade',
            'proporcao',
        ],
    data() {
      return {
        quantidade_int: this.quantidade,
        centimetros: this.quantidade * this.proporcao,
        proporcao: this.proporcao,
      }
    },
    methods: {
      updateCentimetros() {
        this.centimetros = this.quantidade_int * this.proporcao;
      },
      updateQuantidade() {
        this.quantidade_int = Math.floor(this.centimetros / this.proporcao)
        //this.quantidade = this.centimetros / this.proporcao;
      }
    }
  }
  </script>
    