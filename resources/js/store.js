import { reactive, computed } from 'vue'

const state = reactive({
  user: null,
  token: localStorage.getItem('token'),
  sede: localStorage.getItem('sede') || 'torre',
  ronda: null,
  rooms: [],
  historial: [],
  currentView: 'login',
  loading: false,
  toast: null
})

export const useStore = () => {
  const setUser = (user) => { state.user = user }
  const setToken = (token) => { 
    state.token = token 
    if (token) localStorage.setItem('token', token)
    else localStorage.removeItem('token')
  }
  const setSede = (sede) => {
    state.sede = sede
    localStorage.setItem('sede', sede)
  }
  const setRonda = (ronda) => { state.ronda = ronda }
  const setRooms = (rooms) => { state.rooms = rooms }
  const setHistorial = (historial) => { state.historial = historial }
  const setCurrentView = (view) => { state.currentView = view }
  const setLoading = (loading) => { state.loading = loading }
  const showToast = (message, type = 'success') => {
    state.toast = { message, type }
    setTimeout(() => { state.toast = null }, 3000)
  }

  const isAuthenticated = computed(() => !!state.token)

  return {
    state,
    setUser,
    setToken,
    setSede,
    setRonda,
    setRooms,
    setHistorial,
    setCurrentView,
    setLoading,
    showToast,
    isAuthenticated
  }
}
