<script setup>
import { ref } from 'vue'
const isOpen = ref(false)
</script>

<template>
    <button @click="isOpen = !isOpen" class="w-10 h-10 relative flex items-center justify-center hover:cursor-pointer">
        <div class="w-8 h-1 bg-black rounded-full transition-all duration-300" :class="{ 'opacity-0': isOpen }"></div>

        <div class="absolute w-8 h-1 bg-black rounded-full transition-all duration-300" :class="isOpen
            ? 'rotate-45'
            : '-translate-y-2'"></div>

        <div class="absolute w-8 h-1 bg-black rounded-full transition-all duration-300" :class="isOpen
            ? '-rotate-45'
            : 'translate-y-2'"></div>
    </button>
</template>
