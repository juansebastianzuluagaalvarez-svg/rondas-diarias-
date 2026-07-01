<template>
  <div id="app">
    <Login v-if="currentView === 'login'" />
    <template v-else>
      <div class="app-layout">
        <nav class="sidebar">
          <div class="sidebar-logo">
            <img class="sidebar-logo-img" :src="logo" alt="Logo">
          </div>
          <div class="sidebar-title">Sistema de Llamados</div>
          <div class="sidebar-nav">
            <div :class="['nav-item', currentView === 'dashboard' ? 'nav-active' : '']" @click="irA('dashboard')">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
              Inicio
            </div>
            <div :class="['nav-item', currentView === 'ronda' ? 'nav-active' : '']" @click="irA('ronda')">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
              Ronda de Hoy
            </div>
            <div :class="['nav-item', currentView === 'historial' ? 'nav-active' : '']" @click="irA('historial')">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
              Histórico
            </div>
            <div :class="['nav-item', currentView === 'analisis' ? 'nav-active' : '']" @click="irA('analisis')">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 21l-4.35-4.35M11 4a7 7 0 1 0 0 14 7 7 0 0 0 0-14z"/><path d="M16 5l3 3"/><path d="M19 2l3 3"/></svg>
              Análisis
            </div>
            <div :class="['nav-item', currentView === 'mapa-calor' ? 'nav-active' : '']" @click="irA('mapa-calor')">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
              Mapa de Calor
            </div>
            <div :class="['nav-item', currentView === 'reportes' ? 'nav-active' : '']" @click="irA('reportes')">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
              Reportes
            </div>
            <div :class="['nav-item', currentView === 'configuracion' ? 'nav-active' : '']" @click="irA('configuracion')">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
              Configuración
            </div>
          </div>
        </nav>

        <div class="app-main">
          <header class="app-header">
            <div class="app-header-left">
              <div class="sede-toggle">
                <button :class="['sede-option', sede === 'torre' ? 'active' : '']" @click="store.setSede('torre')">
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                  Torre
                </button>
                <button :class="['sede-option', sede === 'urgencias' ? 'active' : '']" @click="store.setSede('urgencias')">
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                  Urgencias
                </button>
              </div>
              <span class="ronda-status">
                <span :class="['status-dot', ronda?.completada ? 'verde' : 'ambar']"></span>
                {{ ronda?.completada ? 'Ronda completada' : 'Ronda en progreso' }}
              </span>
            </div>
            <div class="app-header-user">
              <div class="app-header-avatar">{{ iniciales }}</div>
              <div class="app-header-info">
                <div class="app-header-name">{{ userName }}</div>
                <div class="app-header-email">{{ userEmail }}</div>
              </div>
              <button class="app-header-logout" @click="handleLogout">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                Cerrar sesión
              </button>
            </div>
          </header>

          <div class="app-content">
            <Transition name="fade" mode="out-in">
              <Dashboard v-if="currentView === 'dashboard'" key="dashboard" />
              <div class="content-wrap" v-else key="subview">
                <div class="view-filters" v-if="currentView === 'ronda'">
                  <div class="vf-group">
                  <div class="vf-item">
                    <span class="vf-label">Piso</span>
                    <select class="vf-select" v-model="filtroPiso">
                      <option value="all">Todos</option>
                      <option value="Piso 9">Piso 9</option>
                      <option value="Piso 8">Piso 8</option>
                      <option value="Piso 7">Piso 7</option>
                      <option value="Piso 6">Piso 6</option>
                      <option value="Piso 5">Piso 5</option>
                      <option value="Piso 2">Piso 2</option>
                      <option value="Piso 1">Piso 1</option>
                    </select>
                  </div>
                  <div class="vf-divider"></div>
                  <div class="vf-item">
                      <div class="toggle-group">
                        <button :class="['toggle-btn', subtabActivo === 'llamados' ? 'active' : '']" @click="subtabActivo = 'llamados'">
                          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 2L11 13"/><path d="M22 2l-7 20-4-9-9-4 20-7z"/></svg>
                          Llamados
                        </button>
                        <button :class="['toggle-btn', subtabActivo === 'baños' ? 'active' : '']" @click="subtabActivo = 'baños'">
                          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 8h16"/><path d="M6 12V8"/><path d="M10 12V8"/><path d="M14 12V8"/><path d="M18 12V8"/><path d="M4 16h16"/><path d="M2 20h20"/></svg>
                          Baños
                        </button>
                      </div>
                    </div>
                    <div class="vf-divider"></div>
                    <div class="vf-item vf-chips">
                      <button :class="['chip-btn', filtroActivo === 'all' ? 'active' : '']" @click="filtroActivo = 'all'">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                        Todos
                      </button>
                      <button :class="['chip-btn', 'chip-verde', filtroActivo === 'funciona' ? 'active' : '']" @click="filtroActivo = 'funciona'">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6L9 17l-5-5"/></svg>
                        Funciona
                      </button>
                      <button :class="['chip-btn', 'chip-rojo', filtroActivo === 'no-funciona' ? 'active' : '']" @click="filtroActivo = 'no-funciona'">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M18 6L6 18"/><path d="M6 6l12 12"/></svg>
                        No Funciona
                      </button>
                      <button :class="['chip-btn', 'chip-ambar', filtroActivo === 'aislado' ? 'active' : '']" @click="filtroActivo = 'aislado'">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
                        Aislado
                      </button>
                      <button :class="['chip-btn', 'chip-gris', filtroActivo === 'no-hay' ? 'active' : '']" @click="filtroActivo = 'no-hay'">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/></svg>
                        No Hay
                      </button>
                    </div>
                  </div>
                  <div class="vf-actions">
                    <div class="search-wrap">
                      <svg class="search-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                      <input class="search-input" v-model="busqueda" placeholder="Buscar...">
                    </div>
                  </div>
                </div>
                <div class="view-filters" v-if="currentView === 'historial'">
                  <div class="vf-group">
                    <div class="vf-item">
                      <span class="vf-label">Día</span>
                      <select class="vf-select" v-model="histFiltroFecha">
                        <option value="all">Todos</option>
                        <option v-for="fecha in histFechasUnicas" :key="fecha" :value="fecha">{{ histFormatDate(fecha) }}</option>
                      </select>
                    </div>
                    <div class="vf-divider"></div>
                    <div class="vf-item">
                      <span class="vf-label">Piso</span>
                      <select class="vf-select" v-model="histFiltroPiso">
                        <option value="all">Todos</option>
                        <option value="Piso 9">Piso 9</option>
                        <option value="Piso 8">Piso 8</option>
                        <option value="Piso 7">Piso 7</option>
                        <option value="Piso 6">Piso 6</option>
                        <option value="Piso 5">Piso 5</option>
                        <option value="Piso 2">Piso 2</option>
                        <option value="Piso 1">Piso 1</option>
                      </select>
                    </div>
                    <div class="vf-divider"></div>
                    <div class="vf-item">
                      <div class="toggle-group">
                        <button :class="['toggle-btn', !histSoloBaños ? 'active' : '']" @click="histSoloBaños = false">
                          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 2L11 13"/><path d="M22 2l-7 20-4-9-9-4 20-7z"/></svg>
                          Llamados
                        </button>
                        <button :class="['toggle-btn', histSoloBaños ? 'active' : '']" @click="histSoloBaños = true">
                          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 8h16"/><path d="M6 12V8"/><path d="M10 12V8"/><path d="M14 12V8"/><path d="M18 12V8"/><path d="M4 16h16"/><path d="M2 20h20"/></svg>
                          Baños
                        </button>
                      </div>
                    </div>
                  </div>
                  <div class="vf-actions">
                    <div class="search-wrap">
                      <svg class="search-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                      <input class="search-input" v-model="histFiltroBusqueda" placeholder="Buscar...">
                    </div>
                  </div>
                </div>
                <Transition name="fade" mode="out-in">
                  <Ronda v-if="currentView === 'ronda'" :filtroPiso="filtroPiso" :subtabActivo="subtabActivo" v-model:filtroActivo="filtroActivo" :busqueda="busqueda" key="ronda" />
                  <Historial v-else-if="currentView === 'historial'"
                    :filtroFecha="histFiltroFecha"
                    :filtroPiso="histFiltroPiso"
                    :filtroBusqueda="histFiltroBusqueda"
                    :soloBaños="histSoloBaños"
                    @update:filtroPiso="histFiltroPiso = $event"
                    key="historial" />
                  <Analisis v-else-if="currentView === 'analisis'" key="analisis" />
                  <MapaCalor v-else-if="currentView === 'mapa-calor'" key="mapa-calor" />
                  <Reportes v-else-if="currentView === 'reportes'" key="reportes" />
                  <Configuracion v-else-if="currentView === 'configuracion'" key="configuracion" />
                </Transition>
              </div>
            </Transition>
          </div>
        </div>
      </div>
    </template>

    <Transition name="toast">
      <div v-if="store.state.toast" :class="['toast', store.state.toast.type]">
        {{ store.state.toast.message }}
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { computed, onMounted, ref, watch } from 'vue'
import { useStore } from './store'
import { api } from './api'
import Login from './components/Login.vue'
import Dashboard from './components/Dashboard.vue'
import Ronda from './components/Ronda.vue'
import Historial from './components/Historial.vue'
import Analisis from './components/Analisis.vue'
import MapaCalor from './components/MapaCalor.vue'
import Reportes from './components/Reportes.vue'
import Configuracion from './components/Configuracion.vue'
import logo from '../images/logo.png'

const store = useStore()

const isAuthenticated = computed(() => store.isAuthenticated.value)
const currentView = computed(() => store.state.currentView)
const user = computed(() => store.state.user)
const sede = computed(() => store.state.sede)
const ronda = computed(() => store.state.ronda)

watch(sede, () => {
  if (isAuthenticated.value) loadData()
})

const userName = computed(() => user.value?.name || 'Administrador')
const userRole = computed(() => user.value?.role || 'Administrador')
const userEmail = computed(() => user.value?.email || 'admin@cacsantabarbara.co')
const iniciales = computed(() => {
  const parts = userName.value.split(' ')
  if (parts.length >= 2) return parts[0][0] + parts[parts.length - 1][0]
  return parts[0][0]
})

const setCurrentView = (view) => store.setCurrentView(view)

const filtroPiso = ref('all')
const subtabActivo = ref('llamados')
const filtroActivo = ref('all')
const busqueda = ref('')

const histFiltroFecha = ref('all')
const histFiltroPiso = ref('all')
const histFiltroBusqueda = ref('')
const histSoloBaños = ref(false)

const histFechasUnicas = computed(() => {
  const fechas = new Set((store.state.historial || []).map(h => h.date))
  return Array.from(fechas).sort((a, b) => b.localeCompare(a))
})

const histFormatDate = (fechaStr) => {
  return new Date(fechaStr + 'T12:00:00').toLocaleDateString('es-CO', { 
    weekday: 'long', 
    year: 'numeric', 
    month: 'long', 
    day: 'numeric' 
  })
}

const irA = (view) => {
  setCurrentView(view)
}

const handleLogout = async () => {
  try {
    await api.logout()
  } catch (_) {}
  store.setUser(null)
  store.setToken(null)
  store.setCurrentView('login')
}

const loadData = async () => {
  try {
    store.setLoading(true)
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

onMounted(() => {
  if (isAuthenticated.value) {
    store.setCurrentView('dashboard')
    loadData()
    api.getUser().catch(() => {
      store.setToken(null)
      store.setUser(null)
      store.setCurrentView('login')
    })
  }
})
</script>

<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
:root {
  --azul: #1d4ed8;
  --text: #0f172a;
  --text2: #64748b;
  --border: #e2e8f0;
  --surface: #fff;
  --radius-sm: 6px;
  --radius-md: 10px;
  --radius-lg: 14px;
  --shadow-md: 0 2px 12px rgba(0,0,0,0.06);
  --shadow-lg: 0 4px 24px rgba(0,0,0,0.1);
  --mono: 'JetBrains Mono', monospace;
  --verde-bg: #dcfce7;
  --verde-texto: #15803d;
  --rojo-bg: #fce4ec;
  --rojo-texto: #b91c1c;
  --amarillo-bg: #fef3c7;
  --amarillo-texto: #b45309;
  --gris-bg: #f1f5f9;
  --gris-texto: #64748b;
}
body {
  font-family: 'Inter', 'Segoe UI', sans-serif;
  background: #f4f6f9;
  -webkit-font-smoothing: antialiased;
}
#app {
  height: 100vh;
  overflow: hidden;
}

/* LAYOUT */
.app-layout {
  display: flex;
  height: 100vh;
  background: #f4f6f9;
  overflow: hidden;
}

.app-main {
  flex: 1;
  display: flex;
  flex-direction: column;
  min-width: 0;
}

/* SIDEBAR */
.sidebar {
  width: 230px;
  min-width: 230px;
  background: #1e2d5a;
  color: #fff;
  display: flex;
  flex-direction: column;
  height: 100vh;
  overflow-y: auto;
}
.sidebar-logo {
  padding: 18px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-bottom: 1px solid rgba(255,255,255,0.08);
}
.sidebar-logo-img {
  height: 36px;
  width: auto;
}
.sidebar-title {
  padding: 14px 18px 10px;
  font-size: 13px;
  font-weight: 700;
  color: #fff;
  letter-spacing: 0.3px;
}
.sidebar-nav {
  flex: 1;
  padding: 4px 12px 12px;
}
.nav-item {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 9px 10px;
  border-radius: 6px;
  font-size: 13px;
  font-weight: 500;
  color: rgba(255,255,255,0.7);
  cursor: pointer;
  transition: all 0.15s;
  position: relative;
}
.nav-item:hover {
  color: #fff;
  background: rgba(255,255,255,0.06);
}
.nav-active {
  color: #fff;
  background: rgba(255,255,255,0.12);
}
.nav-active::before {
  content: '';
  position: absolute;
  left: -12px;
  top: 4px;
  bottom: 4px;
  width: 3px;
  background: #fff;
  border-radius: 0 3px 3px 0;
}
.nav-item svg {
  width: 18px;
  height: 18px;
  flex-shrink: 0;
  opacity: 0.8;
}

/* HEADER */
.app-header {
  background: #1e2d5a;
  padding: 10px 28px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  min-height: 58px;
  flex-shrink: 0;
}
.app-header-left {
  display: flex;
  align-items: center;
  gap: 14px;
}
.sede-toggle {
  display: flex;
  border: 1px solid rgba(255,255,255,0.12);
  border-radius: 7px;
  overflow: hidden;
}
.sede-option {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  padding: 4px 11px;
  border: none;
  background: transparent;
  color: rgba(255,255,255,0.55);
  font-size: 11px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
  font-family: inherit;
  white-space: nowrap;
}
.sede-option:hover {
  color: rgba(255,255,255,0.85);
  background: rgba(255,255,255,0.06);
}
.sede-option.active {
  background: rgba(255,255,255,0.15);
  color: #fff;
}
.sede-option svg {
  opacity: 0.6;
}
.sede-option.active svg {
  opacity: 1;
}
.ronda-status {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 12px;
  color: rgba(255,255,255,0.6);
}
.status-dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
}
.status-dot.verde { background: #4ade80; }
.status-dot.ambar { background: #fbbf24; }
.app-header-user {
  display: flex;
  align-items: center;
  gap: 12px;
}
.app-header-avatar {
  width: 38px;
  height: 38px;
  border-radius: 50%;
  background: #e2e8f0;
  color: #1e2d5a;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  font-size: 13px;
  flex-shrink: 0;
  letter-spacing: -0.5px;
}
.app-header-info {
  line-height: 1.3;
}
.app-header-name {
  font-size: 13px;
  font-weight: 700;
  color: #fff;
}
.app-header-email {
  font-size: 10px;
  color: rgba(255,255,255,0.4);
}
.app-header-logout {
  display: flex;
  align-items: center;
  gap: 6px;
  padding: 6px 14px;
  margin-left: 6px;
  border: 1px solid rgba(255,255,255,0.5);
  border-radius: 6px;
  background: transparent;
  color: #fff;
  font-size: 12px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.15s;
  font-family: inherit;
}
.app-header-logout:hover {
  background: rgba(255,255,255,0.1);
}
.app-header-logout svg {
  width: 14px;
  height: 14px;
}

/* VIEW TABS */
.view-tabs {
  display: flex;
  align-items: center;
  background: rgba(255,255,255,0.85);
  backdrop-filter: blur(12px);
  border-bottom: 1px solid var(--border);
  padding: 0 1.5rem;
  flex-shrink: 0;
}
.vt-tab {
  padding: 0.75rem 1.25rem;
  cursor: pointer;
  font-size: 0.85rem;
  font-weight: 500;
  color: var(--text2);
  border-bottom: 2px solid transparent;
  transition: all 0.2s ease;
  white-space: nowrap;
}
.vt-tab:hover {
  color: var(--text);
  background: rgba(13,45,107,0.03);
}
.vt-tab.active {
  color: var(--azul);
  border-bottom-color: var(--azul);
  font-weight: 600;
}

/* CONTENT */
.app-content {
  flex: 1;
  overflow-y: auto;
  min-height: 0;
}
.content-wrap {
  background: var(--surface);
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow-md);
  margin: 1rem 1.5rem;
  overflow-y: auto;
  min-height: 0;
}

/* VIEW FILTERS */
.view-filters {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 14px 20px;
  flex-wrap: nowrap;
  position: sticky;
  top: 0;
  background: #fff;
  z-index: 10;
  border: 1.5px solid #e2e8f0;
  border-top: 3px solid #0D2D6B;
  border-radius: 14px;
  margin-bottom: 14px;
  box-shadow: 0 2px 12px rgba(13,45,107,0.06);
}
.vf-group {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  flex-wrap: nowrap;
  flex: 1;
  min-width: 0;
  overflow: hidden;
}
.vf-item {
  display: flex;
  align-items: center;
  gap: 0.4rem;
}
.vf-label {
  font-size: 0.6rem;
  font-weight: 700;
  color: #94a3b8;
  text-transform: uppercase;
  letter-spacing: 0.06em;
  white-space: nowrap;
}
.vf-select {
  padding: 5px 10px;
  border: 1.5px solid #e2e8f0;
  border-radius: 8px;
  font-size: 0.78rem;
  background: #f8fafc;
  color: #0f172a;
  font-family: inherit;
  cursor: pointer;
  transition: border 0.15s;
}
.vf-select:focus { outline: none; border-color: #3b82f6; background: #fff; }
.vf-divider {
  width: 1px;
  height: 22px;
  background: #e2e8f0;
  flex-shrink: 0;
}
.toggle-group {
  display: flex;
  background: #f1f5f9;
  border-radius: 9px;
  padding: 3px;
  border: 1px solid #e2e8f0;
  gap: 2px;
}
.toggle-btn {
  display: inline-flex;
  align-items: center;
  gap: 0.3rem;
  padding: 0.3rem 0.65rem;
  border: none;
  background: transparent;
  font-size: 0.72rem;
  font-weight: 600;
  color: #64748b;
  cursor: pointer;
  transition: all .18s;
  font-family: inherit;
  border-radius: 6px;
}
.toggle-btn:hover { color: #0f172a; background: rgba(0,0,0,0.05); }
.toggle-btn.active {
  background: #0D2D6B;
  color: #fff;
  box-shadow: 0 3px 10px rgba(13,45,107,0.3);
}
.toggle-btn svg { opacity: 0.6; }
.toggle-btn.active svg { opacity: 1; }
.vf-chips {
  display: flex;
  gap: 0.3rem;
  flex-wrap: nowrap;
}
.chip-btn {
  display: inline-flex;
  align-items: center;
  gap: 0.3rem;
  padding: 5px 12px;
  border-radius: 100px;
  border: 1.5px solid #e2e8f0;
  font-size: 0.72rem;
  font-weight: 600;
  cursor: pointer;
  transition: all .18s;
  font-family: inherit;
  background: #f8fafc;
  color: #64748b;
}
.chip-btn:hover { border-color: #94a3b8; background: #f1f5f9; color: #334155; }
.chip-btn.active { background: #0D2D6B; color: #fff; border-color: #0D2D6B; box-shadow: 0 3px 10px rgba(13,45,107,0.25); }
.chip-btn.chip-verde.active { background: #15803d; border-color: #15803d; box-shadow: 0 3px 10px rgba(21,128,61,0.25); }
.chip-btn.chip-rojo.active  { background: #b91c1c; border-color: #b91c1c; box-shadow: 0 3px 10px rgba(185,28,28,0.25); }
.chip-btn.chip-ambar.active { background: #b45309; border-color: #b45309; box-shadow: 0 3px 10px rgba(180,83,9,0.25); }
.chip-btn.chip-gris.active  { background: #64748b; border-color: #64748b; box-shadow: 0 3px 10px rgba(100,116,139,0.25); }
.vf-actions {
  flex-shrink: 0;
}
.search-wrap {
  position: relative;
  display: flex;
  align-items: center;
}
.search-icon {
  position: absolute;
  left: 9px;
  color: #94a3b8;
  pointer-events: none;
}
.search-input {
  padding: 6px 10px 6px 30px;
  border: 1.5px solid #e2e8f0;
  border-radius: 8px;
  font-size: 0.78rem;
  font-family: inherit;
  background: #f8fafc;
  color: #0f172a;
  outline: none;
  min-width: 160px;
  transition: border 0.15s, background 0.15s;
}
.search-input:focus {
  border-color: #3b82f6;
  background: #fff;
}

/* TRANSITIONS */
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.2s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}

/* TOAST */
.toast {
  position: fixed;
  bottom: 2rem;
  left: 50%;
  transform: translateX(-50%);
  padding: 0.7rem 1.5rem;
  border-radius: var(--radius-md);
  font-size: 0.85rem;
  font-weight: 600;
  z-index: 9999;
  box-shadow: var(--shadow-lg);
  pointer-events: none;
  white-space: nowrap;
}
.toast.success { background: #15803d; color: #fff; }
.toast.error { background: #b91c1c; color: #fff; }
.toast.info { background: var(--azul); color: #fff; }
.toast-enter-active { transition: all 0.3s cubic-bezier(0.16,1,0.3,1); }
.toast-leave-active { transition: all 0.2s ease; }
.toast-enter-from { opacity: 0; transform: translateX(-50%) translateY(20px); }
.toast-leave-to { opacity: 0; transform: translateX(-50%) translateY(10px); }

/* ===== MOBILE RESPONSIVE ===== */
@media (max-width: 768px) {
  .app-layout { flex-direction: column; }

  /* Sidebar → Bottom nav */
  .sidebar {
    width: 100%; min-width: 100%; height: auto;
    flex-direction: row; padding: 0; order: 2;
    position: fixed; bottom: 0; left: 0; z-index: 100;
    background: #1e2d5a;
    overflow-x: auto; overflow-y: hidden;
    -webkit-overflow-scrolling: touch;
  }
  .sidebar-logo, .sidebar-title { display: none; }
  .sidebar-nav {
    display: flex; flex-direction: row; width: 100%;
    padding: 0 4px; gap: 2px; justify-content: space-around;
  }
  .nav-item {
    flex-direction: column; gap: 3px; padding: 6px 8px 4px;
    font-size: 10px; text-align: center; border-radius: 6px;
    white-space: nowrap; min-width: 0;
  }
  .nav-item svg { width: 20px; height: 20px; margin: 0 auto; }
  .nav-active::before { display: none; }
  .nav-active { background: rgba(255,255,255,0.12); }

  /* Main */
  .app-main { overflow-y: auto; padding-bottom: 62px; }
  .content-wrap { overflow-y: visible; height: auto; min-height: 0; max-height: none; }

  /* Header: una sola fila */
  .app-header { flex-wrap: nowrap; align-items: center; padding: 7px 10px; gap: 6px; }
  .app-header-left { flex: 1; min-width: 0; flex-wrap: nowrap; gap: 6px; overflow: hidden; }
  .sede-option { font-size: 0; padding: 5px 7px; gap: 0; }
  .sede-option svg { width: 14px; height: 14px; opacity: 1; }
  .rs-start-btn { font-size: 10px; padding: 4px 9px 4px 7px; gap: 5px; white-space: nowrap; }
  .rs-done { font-size: 10px; padding: 4px 10px; gap: 5px; white-space: nowrap; }
  .app-header-user { flex-shrink: 0; gap: 4px; }
  .app-header-info { display: none; }
  .app-header-avatar { width: 30px; height: 30px; font-size: 11px; }
  .app-header-logout { font-size: 0; padding: 6px 8px; margin-left: 0; gap: 0; }
  .app-header-logout svg { width: 14px; height: 14px; }

  /* View filters card: vertical */
  .view-filters {
    flex-direction: column; align-items: stretch;
    padding: 10px 12px; gap: 8px;
    border-radius: 12px; margin-bottom: 10px;
  }
  .vf-group { flex-wrap: wrap; gap: 6px; }
  .vf-divider { display: none; }
  .vf-actions { width: 100%; }
  .vf-chips { flex-wrap: wrap; gap: 4px; }
  .search-wrap, .search-input { width: 100%; min-width: 0; }
  .chip-btn { font-size: 0.68rem; padding: 4px 10px; }
  .toggle-btn { font-size: 0.68rem; padding: 0.28rem 0.5rem; }
  .vf-select { font-size: 0.75rem; }
}
</style>
