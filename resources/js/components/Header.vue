<template>
  <div class="header">
    <div class="logo">
      <img class="logo-img" :src="logo" alt="Logo">
      <div class="logo-text">
        <h1>Sistema de Llamados</h1>
        <p>Rondas Diarias — CAC Santa Bárbara</p>
      </div>
    </div>
    <div class="header-right">
      <div class="db-status">
        <div class="db-dot conectado"></div>
        <span>Conectado</span>
      </div>
      <div class="fecha-badge" id="fechaHoy">{{ fechaHoy }}</div>
      <div class="fecha-badge reloj" id="relojVivo">{{ horaActual }}</div>
      <button class="btn btn-header" @click="irAlPanel" id="btnPanel" v-if="store.currentView !== 'dashboard'">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
        Panel
      </button>
      <button class="btn btn-header" @click="handleLogout" id="btnLogout">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
        Salir
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { useStore } from '../store'
import { api } from '../api'
import logo from '../../images/logo.png'

const store = useStore()
const fechaHoy = ref('')
const horaActual = ref('')
let timer = null

const updateDateTime = () => {
  const now = new Date()
  fechaHoy.value = now.toLocaleDateString('es-CO', { 
    weekday: 'long', 
    year: 'numeric', 
    month: 'long', 
    day: 'numeric' 
  })
  horaActual.value = now.toLocaleTimeString('es-CO', { 
    hour: '2-digit', 
    minute: '2-digit', 
    second: '2-digit' 
  })
}

const irAlPanel = () => {
  store.setCurrentView('dashboard')
}

const handleLogout = async () => {
  try {
    await api.logout()
  } catch (err) {
    store.showToast('Error al cerrar sesión', 'error')
  } finally {
    store.setUser(null)
    store.setToken(null)
    store.setCurrentView('login')
  }
}

onMounted(() => {
  updateDateTime()
  timer = setInterval(updateDateTime, 1000)
})

onUnmounted(() => {
  if (timer) clearInterval(timer)
})
</script>

<style scoped>
.reloj {
  font-size: 0.82rem !important;
  font-weight: 500;
  letter-spacing: 0.5px;
}

.btn-header {
  display: inline-flex !important;
  align-items: center !important;
  gap: 0.4rem !important;
}
</style>
