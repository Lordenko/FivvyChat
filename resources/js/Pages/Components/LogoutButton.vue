<script setup>
import {ref} from 'vue'
import {router} from '@inertiajs/vue3'

const {disabled} = defineProps({disabled: Boolean})

const isProcessing = ref(false)

const logout = () => {
    if (isProcessing.value || disabled) return
    isProcessing.value = true

    router.delete(route('logout'), {
        onFinish: () => isProcessing.value = false
    })
}
</script>

<template>
    <button
        @click="logout"
        :disabled="isProcessing || disabled"
        class="w-full bg-zinc-800 select-none py-4 rounded-[20px] text-white flex items-center justify-center border border-zinc-700 transition"
        :class="{
      'hover:bg-purple-700 hover:cursor-pointer': !(isProcessing || disabled),
      '!bg-zinc-800 text-zinc-400': isProcessing
    }"
    >
        {{ isProcessing ? 'Logging out...' : 'Logout' }}
    </button>
</template>

