<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    isOpen: Boolean,
})

const emit = defineEmits(['update:isOpen'])

const isProcessing = ref(false)

</script>

<template>
    <button
        @click="$emit('update:isOpen', !isOpen)"
        class="w-full bg-zinc-800 select-none py-4 rounded-[20px] text-white flex items-center justify-center border border-zinc-700 transition hover:bg-purple-700 hover:cursor-pointer"
    >
        Профіль
    </button>
</template>

