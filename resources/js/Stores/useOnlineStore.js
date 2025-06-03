import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useOnlineStore = defineStore('online', () => {
    const onlineMap = ref({})
    const leaveTimeouts = {}

    const setOnline = (userId) => {
        onlineMap.value[userId] = true
        if (leaveTimeouts[userId]) {
            clearTimeout(leaveTimeouts[userId])
            delete leaveTimeouts[userId]
        }
    }

    const setOfflineWithDelay = (userId, delay = 5000) => {
        leaveTimeouts[userId] = setTimeout(() => {
            onlineMap.value[userId] = false
            delete leaveTimeouts[userId]
        }, delay)
    }

    const isOnline = (userId) => !!onlineMap.value[userId]

    return {
        onlineMap,
        setOnline,
        setOfflineWithDelay,
        isOnline,
    }
})
