import { usePage } from '@inertiajs/vue3'
import { ref, computed } from 'vue'

const page = usePage()
const user = computed(() => page.props.auth?.user ?? {})
export function useCurrentUser() {
    return { user }
}
