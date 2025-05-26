<!-- components/TextInput.vue -->
<script setup>
const modelValue = defineModel()

defineProps({
    name: {
        type: String,
        required: true
    },
    type: {
        type: String,
        default: 'text'
    },
    placeholder: String,
    message: String
})
</script>

<template>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">{{ name }}</label>
        <input
            :type="type"
            :placeholder="placeholder"
            v-model="modelValue"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
        />
        <p v-if="message" class="text-sm text-red-500 mt-1">{{ message }}</p>
    </div>
</template>
