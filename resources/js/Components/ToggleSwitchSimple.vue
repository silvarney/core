<template>
    <label class="flex items-center cursor-pointer">
        <input 
            type="checkbox" 
            :id="id" 
            :checked="modelValue === 1"
            @change="$emit('update:modelValue', $event.target.checked ? 1 : 0)"
            class="mr-2"
        />
        <span class="text-sm font-medium text-gray-700">{{ label }}</span>
    </label>
</template>

<script setup>
defineProps({
    id: String,
    label: String,
    modelValue: Number
})

defineEmits(['update:modelValue'])
</script>