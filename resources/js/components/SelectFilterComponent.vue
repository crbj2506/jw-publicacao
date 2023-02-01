<template>
    <div class="input-group mb-3"
        :class="classinputgroup"
        >
        <span class="input-group-text">Filtro:</span>
        <input type="text" class="form-control" v-model="filtro">
        <span class="input-group-text">{{label}}</span>
        <select 
            class="form-select w-50"
            :class="class"
            :id="id" 
            :name="name" 
            :required="required" 
            >
            <option value="">{{option}}</option>
            <option v-for="opcao in listafiltrada" :value="opcao.value" :selected="opcao.value == publicacao_id">{{opcao.texto}}</option>
        </select>
        <div 
            class="text-start"
            :class="classmessage"
            >{{ message }}</div>
    </div>
</template>

<script>
    export default {
        mounted() { 
            this.opcoes = this.listafiltrada = JSON.parse(this.options)
            console.log(this.opcoes)
        },
        props: [
            'class',
            'classinputgroup',
            'classmessage',
            'id',
            'label',
            'message',
            'name',
            'option',
            'options',
            'publicacao_id',
            'required',
        ],
        data(){
            return{
                filtro: null,
                opcoes: [],
                listafiltrada: [],
            }
        },
        watch:{ //Funções que monitoram qualquer mudança no valor // As funções devem ter o mesmo nome do atributo
            filtro(valorNovo){
                this.listafiltrada = this.opcoes.filter(opcao => opcao.texto.toLowerCase().match(valorNovo.toLowerCase()))
            }
        },
    }
</script>