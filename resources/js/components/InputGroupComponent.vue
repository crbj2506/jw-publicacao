<template>
        <div class="input-group has-validation">
            <span class="input-group-text">{{label}}</span>
            <input 
                :maxlength="maxlength"
                :type="type" 
                :step="step" 
                class="form-control" 
                :class="class" 
                :name="name" 
                :value="value" 
                :disabled="disabled"
                :required="required"
                :id="id"
                v-money="monay"
                v-maska 
                :data-maska="mascara"
                >
            <div class="invalid-feedback text-start">{{ message }}</div>
        </div>
</template>

<script>
    import { vMaska } from "maska"
    export default {
        props: [ 
            'label',
            'name', 
            'value',
            'disabled',
            'class',
            'message',
            'type',
            'step',
            'style',
            'id',
            'monay',
            'mascara',
            'maxlength',
            'required',
        ],
        directives: { 
            maska: vMaska 
        },
    }
</script>