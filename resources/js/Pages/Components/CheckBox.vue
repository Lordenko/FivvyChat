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
        <input
            type="checkbox"
            v-model="modelValue"
            class="appearance-none h-5 w-5 rounded border border-zinc-600 bg-zinc-800 checked:bg-purple-600 checked:border-purple-600 focus:ring-0 focus:outline-none transition duration-200"
        />
        <span class="select-none ml-2 text-sm text-purple-300">{{ name }}</span>
    </label>


</template>
