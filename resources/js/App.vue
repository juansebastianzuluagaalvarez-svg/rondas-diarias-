<template>
  <div id="app">
    <Login v-if="!isAuthenticated" />
    <template v-else>
      <Header />
      <div class="tabs" v-if="currentView !== 'dashboard'">
        <div :class="['tab', currentView === 'ronda' ? 'active' : '']" @click="setCurrentView('ronda')">Ronda de Hoy</div>
        <div :class="['tab', currentView === 'historial' ? 'active' : '']" @click="setCurrentView('historial')">Historial</div>
        <div class="tab-filters" v-if="currentView === 'ronda'">
          <div class="filter-group">
            <div class="filter-item">
              <span class="filter-label">Piso</span>
              <select class="filter-select" v-model="filtroPiso">
                <option value="all">Todos</option>
                <option value="Piso 7">Piso 7</option>
                <option value="Piso 8">Piso 8</option>
                <option value="UCI">UCI</option>
                <option value="UCIN">UCIN</option>
              </select>
            </div>
            <div class="filter-divider"></div>
            <div class="filter-item">
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
            <div class="filter-divider"></div>
            <div class="filter-item filter-chips">
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
          <div class="filter-actions">
            <div class="search-wrap">
              <svg class="search-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
              <input class="search-input" v-model="busqueda" placeholder="Buscar...">
            </div>
          </div>
        </div>
        <div class="tab-filters" v-if="currentView === 'historial'">
          <div class="filter-group">
            <div class="filter-item">
              <span class="filter-label">Día</span>
              <select class="filter-select" v-model="histFiltroFecha">
                <option value="all">Todos</option>
                <option v-for="fecha in histFechasUnicas" :key="fecha" :value="fecha">{{ histFormatDate(fecha) }}</option>
              </select>
            </div>
            <div class="filter-divider"></div>
            <div class="filter-item">
              <span class="filter-label">Piso</span>
              <select class="filter-select" v-model="histFiltroPiso">
                <option value="all">Todos</option>
                <option value="Piso 7">Piso 7</option>
                <option value="Piso 8">Piso 8</option>
                <option value="UCI">UCI</option>
                <option value="UCIN">UCIN</option>
              </select>
            </div>
            <div class="filter-divider"></div>
            <div class="filter-item">
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
          <div class="filter-actions">
            <div class="search-wrap">
              <svg class="search-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
              <input class="search-input" v-model="histFiltroBusqueda" placeholder="Buscar...">
            </div>
          </div>
        </div>
      </div>
      <div v-if="loading" class="skeleton-overlay">
        <div class="skeleton-card">
          <div class="skeleton-shape skeleton-title"></div>
          <div class="skeleton-shape skeleton-text"></div>
          <div class="skeleton-row">
            <div class="skeleton-shape skeleton-box"></div>
            <div class="skeleton-shape skeleton-box"></div>
            <div class="skeleton-shape skeleton-box"></div>
            <div class="skeleton-shape skeleton-box"></div>
          </div>
          <div class="skeleton-shape skeleton-bar"></div>
        </div>
      </div>
      <Transition name="fade" mode="out-in">
        <Dashboard v-if="currentView === 'dashboard'" key="dashboard" />
        <div class="content-wrap" v-else-if="currentView !== 'dashboard'" key="subview">
          <Transition name="fade" mode="out-in">
            <Ronda v-if="currentView === 'ronda'" :filtroPiso="filtroPiso" :subtabActivo="subtabActivo" v-model:filtroActivo="filtroActivo" :busqueda="busqueda" key="ronda" />
            <Historial v-else-if="currentView === 'historial'"
              :filtroFecha="histFiltroFecha"
              :filtroPiso="histFiltroPiso"
              :filtroBusqueda="histFiltroBusqueda"
              :soloBaños="histSoloBaños"
              key="historial" />
            <Analisis v-else-if="currentView === 'analisis'" key="analisis" />
          </Transition>
        </div>
      </Transition>
    </template>

    <Transition name="toast">
      <div v-if="store.state.toast" :class="['toast', store.state.toast.type]">
        {{ store.state.toast.message }}
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import { useStore } from './store'
import { api } from './api'
import Login from './components/Login.vue'
import Header from './components/Header.vue'
import Dashboard from './components/Dashboard.vue'
import Ronda from './components/Ronda.vue'
import Historial from './components/Historial.vue'
import Analisis from './components/Analisis.vue'

const store = useStore()

const isAuthenticated = computed(() => store.isAuthenticated.value)
const currentView = computed(() => store.state.currentView)
const loading = computed(() => store.state.loading)
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
#app {
  height: 100vh;
  overflow: hidden;
  display: flex;
  flex-direction: column;
}

.tabs {
  display: flex;
  align-items: center;
  background: rgba(255, 255, 255, 0.85);
  backdrop-filter: blur(12px);
  border-bottom: 1px solid var(--border);
  padding: 0 1.5rem;
  overflow-x: visible;
  box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
  position: sticky;
  top: 58px;
  z-index: 99;
}

.tab {
  padding: 0.85rem 1.35rem;
  cursor: pointer;
  font-size: 0.88rem;
  font-weight: 500;
  color: var(--text2);
  border-bottom: 2px solid transparent;
  transition: all 0.2s ease;
  white-space: nowrap;
}

.tab:hover {
  color: var(--text);
  background: rgba(13, 45, 107, 0.03);
}

.tab.active {
  color: var(--azul);
  border-bottom-color: var(--azul);
  font-weight: 600;
}

.content-wrap {
  background: var(--surface);
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow-md);
  margin: 1rem 1.5rem;
  overflow-y: auto;
  flex: 1;
  min-height: 0;
}

.skeleton-overlay {
  position: fixed;
  inset: 0;
  background: linear-gradient(160deg, #f0f4f8 0%, #f8fafc 50%, #f1f5f9 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 200;
}

.skeleton-card {
  background: var(--surface);
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow-lg);
  padding: 2rem;
  width: 520px;
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.skeleton-shape {
  background: linear-gradient(90deg, #e2e8f0 25%, #f1f5f9 50%, #e2e8f0 75%);
  background-size: 200% 100%;
  animation: shimmer 1.5s ease-in-out infinite;
  border-radius: var(--radius-sm);
}

.skeleton-title {
  width: 60%;
  height: 24px;
}

.skeleton-text {
  width: 40%;
  height: 14px;
}

.skeleton-row {
  display: flex;
  gap: 1rem;
}

.skeleton-box {
  flex: 1;
  height: 56px;
}

.skeleton-bar {
  width: 100%;
  height: 12px;
}

@keyframes shimmer {
  0% { background-position: 200% 0; }
  100% { background-position: -200% 0; }
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.25s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

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

.toast.success {
  background: #15803d;
  color: #fff;
}

.toast.error {
  background: #b91c1c;
  color: #fff;
}

.toast.info {
  background: var(--azul);
  color: #fff;
}

.toast-enter-active {
  transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}

.toast-leave-active {
  transition: all 0.2s ease;
}

.toast-enter-from {
  opacity: 0;
  transform: translateX(-50%) translateY(20px);
}

.toast-leave-to {
  opacity: 0;
  transform: translateX(-50%) translateY(10px);
}

@media (max-width: 600px) {
  .content-wrap {
    margin: 0.75rem 1rem;
  }
}
</style>
