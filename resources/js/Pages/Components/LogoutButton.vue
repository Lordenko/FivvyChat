<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const { disabled } = defineProps({ disabled: Boolean })

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
    <button @click="logout" :disabled="isProcessing || disabled"
        class="w-full select-none py-4 rounded-[20px] bg-black text-white flex items-center justify-center border transition"
        :class="{
            'hover:cursor-pointer hover:bg-gray-900': !(isProcessing || disabled),
            '!bg-gray-400': isProcessing
        }">
        {{ isProcessing ? 'Logging out...' : 'Logout' }}
    </button>
</template>
