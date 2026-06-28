<template>
  <div class="header">
    <div class="logo">
      <img class="logo-img" :src="logo" alt="Logo">
      <div class="logo-divider"></div>
      <div class="logo-text">
        <h1>Sistema de Llamados</h1>
        <p>Rondas Diarias · CAC Santa Bárbara <span class="logo-sede">— {{ sede === 'urgencias' ? 'Urgencias' : 'Torre' }}</span></p>
      </div>
    </div>
    <div class="header-right">

      <div class="header-group">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        <span>{{ fechaHoy }}</span>
      </div>
      <div class="header-sep"></div>
      <div class="header-group reloj">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        <span>{{ horaActual }}</span>
      </div>
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
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useStore } from '../store'
import { api } from '../api'
import logo from '../../images/logo.png'

const store = useStore()
const sede = computed(() => store.state.sede)
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

const irAlPanel = async () => {
  store.setCurrentView('dashboard')
  if (store.state.currentView === 'dashboard') {
    store.setLoading(true)
    try {
      const [rondaData, historialData] = await Promise.all([
        api.getTodayRonda(),
        api.getHistorial()
      ])
      store.setRonda(rondaData.ronda)
      store.setRooms(rondaData.rooms)
      if (historialData.historial.data) {
        store.setHistorial(historialData.historial.data)
      } else if (historialData.historial) {
        store.setHistorial(historialData.historial)
      } else {
        store.setHistorial([])
      }
    } catch (err) {
      store.showToast('Error al cargar datos', 'error')
    } finally {
      store.setLoading(false)
    }
  }
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
.logo {
  display: flex;
  align-items: center;
  gap: 1.25rem;
}
.logo-img {
  height: 36px;
  width: auto;
  display: block;
  filter: drop-shadow(0 2px 6px rgba(0,0,0,0.25));
}
.logo-divider {
  width: 1px;
  height: 26px;
  background: rgba(255,255,255,0.12);
}
.logo-text h1 {
  font-size: 1.05rem;
  font-weight: 700;
  line-height: 1.2;
  letter-spacing: -0.3px;
  background: linear-gradient(90deg, #fff 60%, rgba(255,255,255,0.7));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}
.logo-text p {
  font-size: 0.7rem;
  color: rgba(255,255,255,0.5);
  margin-top: 0.05rem;
  letter-spacing: 0.3px;
}
.logo-sede {
  color: rgba(255,255,255,0.7);
  font-weight: 600;
}

.header-right {
  display: flex;
  align-items: center;
  gap: 0;
}
.header-group {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  padding: 0.3rem 0.75rem;
  font-size: 0.78rem;
  white-space: nowrap;
  color: rgba(255,255,255,0.85);
}
.header-group svg {
  opacity: 0.6;
  flex-shrink: 0;
}
.header-sep {
  width: 1px;
  height: 18px;
  background: rgba(255,255,255,0.08);
  flex-shrink: 0;
}
.reloj {
  font-family: var(--mono);
  letter-spacing: 0.5px;
  color: #fff;
}
.reloj svg {
  opacity: 0.5;
}

.btn-header {
  display: inline-flex !important;
  align-items: center !important;
  gap: 0.35rem !important;
  padding: 0.35rem 0.75rem !important;
  font-size: 0.78rem !important;
  border-radius: 8px !important;
  border: 1px solid rgba(255,255,255,0.1) !important;
  background: rgba(255,255,255,0.04) !important;
  color: rgba(255,255,255,0.8) !important;
  transition: all .15s ease !important;
  margin: 0 0.1rem;
}
.btn-header:hover {
  background: rgba(255,255,255,0.1) !important;
  border-color: rgba(255,255,255,0.2) !important;
  color: #fff !important;
}
.btn-header svg {
  opacity: 0.6;
}
.btn-header:hover svg {
  opacity: 1;
}

@media (max-width: 768px) {
  .logo-text { display: none; }
  .logo-divider { display: none; }
  .header-group { display: none !important; }
  .header-sep { display: none !important; }
  .header { padding: 0.4rem 0.75rem; gap: 0.4rem; }
  .logo-img { height: 28px; }
  .header-right { gap: 0.2rem; }
  .btn-header { font-size: 0.7rem !important; padding: 0.25rem 0.5rem !important; gap: 0.25rem !important; }
}
@media (max-width: 480px) {
  .btn-header svg { display: none; }
  .btn-header { font-size: 0.65rem !important; padding: 0.2rem 0.45rem !important; }
}
</style>
