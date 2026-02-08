<template>
    <label class="flex items-center cursor-pointer relative">
        <input 
            type="checkbox" 
            :id="id" 
            class="sr-only peer" 
            :checked="modelValue === 1"
            @input="handleInput"
        />
        <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:bg-indigo-600">
            <div class="absolute top-[2px] left-[2px] bg-white border border-gray-300 rounded-full h-5 w-5 transition-all peer-checked:translate-x-full peer-checked:border-white"></div>
        </div>
        <span class="ml-3 text-sm font-medium text-gray-700">{{ label }}</span>
    </label>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue'

const props = defineProps({
    id: {
        type: String,
        required: true
    },
    label: {
        type: String,
        required: true
    },
    modelValue: {
        type: Number,
        default: 0
    }
})

const emit = defineEmits(['update:modelValue'])

const handleInput = (event) => {
    emit('update:modelValue', event.target.checked ? 1 : 0)
}
</script>