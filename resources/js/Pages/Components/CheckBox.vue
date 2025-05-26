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
    <label class="flex items-center">
        <input type="checkbox" v-model="modelValue" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
        <span class="select-none ml-2 text-sm text-gray-600">{{ name }}</span>
    </label>
</template>
