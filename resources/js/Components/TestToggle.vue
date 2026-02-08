<template>
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-4">Teste Toggle</h1>
        
        <div class="mb-4">
            <label class="flex items-center">
                <input 
                    type="checkbox" 
                    v-model="testValue"
                    true-value="1"
                    false-value="0"
                    class="mr-3"
                />
                <span>Teste Original ({{ testValue }})</span>
            </label>
        </div>

        <div class="mb-4">
            <ToggleSwitchSimple 
                id="test-simple" 
                label="Toggle Simplificado" 
                v-model="simpleValue" 
            />
            <span class="ml-4">Valor: {{ simpleValue }}</span>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import ToggleSwitchSimple from '@/Components/ToggleSwitchSimple.vue'

const testValue = ref(0)
const simpleValue = ref(0)
</script>