<template>
        <div class="input-group has-validation">
            <span class="input-group-text">{{label}}</span>
            <input 
                v-if="!mascara"
                class="form-control" 
                :class="class" 
                :disabled="disabled"
                :id="id"
                :filename="filename"
                :maxlength="maxlength"
                :name="name" 
                :placeholder="placeholder"
                :type="type" 
                :step="step" 
                :value="value" 
                :required="required"
                >
            <input 
                v-else
                class="form-control" 
                :class="class" 
                :disabled="disabled"
                :id="id"
                :filename="filename"
                :maxlength="maxlength"
                :name="name"
                :placeholder="placeholder"
                :type="type" 
                :step="step" 
                :value="value" 
                :required="required"
                v-maska="mascara"
                >
            <div class="invalid-feedback text-start">{{ message }}</div>
        </div>
            <img v-if="filename" :src="'/storage/'+filename"  class="img-thumbnail">
</template>

<script>
    import { vMaska } from "maska"
    export default {
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
        directives: { 
            maska: vMaska 
        },
    }
</script>