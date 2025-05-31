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
        <label class="block text-sm font-medium text-purple-300 mb-1">{{ name }}</label>

        <input
            :type="type"
            :placeholder="placeholder"
            v-model="modelValue"
            class="w-full px-4 py-2 bg-zinc-900 text-white border border-zinc-700 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-purple-600 outline-none transition-all"
        />

        <p v-if="message" class="text-sm text-red-500 mt-1">{{ message }}</p>

    </div>
</template>
