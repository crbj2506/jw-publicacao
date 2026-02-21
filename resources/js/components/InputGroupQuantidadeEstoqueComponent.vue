<template>
    <div class="input-group">
        <span class="input-group-text">Quantidade</span>
        <input 
            class="form-control"
            :disabled="disabled"
            type="number"
            v-model.number="quantidade_int" 
            @input="updateCentimetros"
            min="0" step="1"
            >
        <input 
            class="form-control"
            :disabled="disabled"
            type="number"
            v-model.number="centimetros_local" 
            @input="updateQuantidade"
            min="0" step="0.1"
            >
        <slot></slot>
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
        centimetros_local: this.quantidade * this.proporcao,
        proporcao_local: this.proporcao,
      }
    },
    methods: {
      updateCentimetros() {
        this.centimetros_local = this.quantidade_int * this.proporcao_local;
      },
      updateQuantidade() {
        this.quantidade_int = Math.floor(this.centimetros_local / this.proporcao_local)
        //this.quantidade = this.centimetros / this.proporcao;
      }
    }
  }
  </script>
    