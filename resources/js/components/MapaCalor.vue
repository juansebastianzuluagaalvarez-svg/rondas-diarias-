<template>
  <div class="mc-wrap">
    <div class="mc-hero">
      <div class="mc-hero-icon">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
      </div>
      <div>
        <h2 class="mc-hero-title">Mapa de Calor</h2>
        <p class="mc-hero-desc">Actividad por piso y hora — {{ vistaLabel }}</p>
      </div>
    </div>

    <div class="mc-kpis">
      <div class="mc-kpi">
        <span class="mc-kpi-num">{{ totalActividad }}</span>
        <span class="mc-kpi-label">Registros</span>
      </div>
      <div class="mc-kpi">
        <span class="mc-kpi-num" style="color:#22c55e">{{ totalFunciona }}</span>
        <span class="mc-kpi-label">Funciona</span>
      </div>
      <div class="mc-kpi">
        <span class="mc-kpi-num" style="color:#ef4444">{{ totalNoFunciona }}</span>
        <span class="mc-kpi-label">No funciona</span>
      </div>
      <div class="mc-kpi">
        <span class="mc-kpi-num" style="color:#f59e0b">{{ totalAislado }}</span>
        <span class="mc-kpi-label">Aislado</span>
      </div>
      <div class="mc-kpi">
        <span class="mc-kpi-num">{{ horaActual }}</span>
        <span class="mc-kpi-label">Hora</span>
      </div>
    </div>

    <div class="mc-filters">
      <div class="mc-chips">
        <button :class="['mc-chip', vista === 'hoy' ? 'active' : '']" @click="vista = 'hoy'">Hoy</button>
        <button :class="['mc-chip', vista === 'semana' ? 'active' : '']" @click="vista = 'semana'">Semana</button>
        <button :class="['mc-chip', vista === 'mes' ? 'active' : '']" @click="vista = 'mes'">Mes</button>
      </div>
      <div class="mc-chips">
        <button :class="['mc-chip', filtroSede === 'all' ? 'active' : '']" @click="filtroSede = 'all'">Todas</button>
        <button :class="['mc-chip', filtroSede === 'torre' ? 'active' : '']" @click="filtroSede = 'torre'">Torre</button>
        <button :class="['mc-chip', filtroSede === 'urgencias' ? 'active' : '']" @click="filtroSede = 'urgencias'">Urgencias</button>
      </div>
      <div class="mc-legend">
        <span class="mc-legend-label">Menos</span>
        <span v-for="l in 5" :key="l" class="mc-legend-cell" :style="{ background: colorForIntensity(l, 5) }"></span>
        <span class="mc-legend-label">Más</span>
      </div>
    </div>

    <div class="mc-heatmap-card">
      <div class="mc-heatmap-scroll">
        <table class="mc-heatmap">
          <thead>
            <tr>
              <th class="mc-hm-piso">Piso</th>
              <th v-for="col in columnas" :key="col.key" class="mc-hm-col" :class="{ 'mc-hm-now': col.isNow }">
                {{ col.label }}
              </th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="row in filas" :key="row.piso">
              <td class="mc-hm-piso">{{ row.piso }}</td>
              <td
                v-for="col in columnas"
                :key="col.key"
                class="mc-hm-cell"
                :style="{ background: colorForIntensity(row.cells[col.key] || 0, maxIntensidad) }"
                @mouseenter="tooltip = { row: row.piso, col: col.key, items: row.detail[col.key] || [] }"
                @mouseleave="tooltip = null"
              >
                <span class="mc-hm-val">{{ row.cells[col.key] || 0 }}</span>
              </td>
            </tr>
            <tr class="mc-hm-total-row">
              <td class="mc-hm-piso">Total</td>
              <td v-for="col in columnas" :key="col.key" class="mc-hm-cell mc-hm-total">
                <span class="mc-hm-val">{{ totalPorColumna[col.key] || 0 }}</span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <Teleport to="body">
        <div v-if="tooltip" class="mc-tooltip" :style="{ top: tooltipY + 'px', left: tooltipX + 'px' }">
          <strong>{{ tooltip.row }} — {{ tooltip.col }}</strong>
          <div class="mc-tt-info">{{ tooltip.items.length }} registro{{ tooltip.items.length !== 1 ? 's' : '' }}</div>
          <div v-for="(item, i) in tooltip.items.slice(0, 8)" :key="i" class="mc-tt-item">
            <span class="mc-tt-room">{{ item.room_name }}</span>
            <span :class="['mc-tt-chip', item.nuevo]">{{ estadoLabel(item.nuevo) }}</span>
          </div>
          <div v-if="tooltip.items.length > 8" class="mc-tt-more">+{{ tooltip.items.length - 8 }} más</div>
        </div>
      </Teleport>
    </div>

    <div class="mc-live">
      <div class="mc-live-header">
        <div class="mc-live-dot"></div>
        <h3>Últimos cambios</h3>
      </div>
      <div class="mc-live-list">
        <div v-for="item in ultimosCambios" :key="item.id" class="mc-live-item">
          <span class="mc-live-time">{{ formatTime(item.created_at) }}</span>
          <span class="mc-live-room">{{ item.room_name }}</span>
          <span class="mc-live-section">{{ item.section }}</span>
          <span :class="['mc-tt-chip', item.nuevo]">{{ estadoLabel(item.nuevo) }}</span>
          <span class="mc-live-user" v-if="item.user">{{ item.user.name || item.user }}</span>
        </div>
        <div v-if="!ultimosCambios.length" class="mc-live-empty">Sin actividad reciente</div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue'
import { useStore } from '../store'

const store = useStore()
const vista = ref('hoy')
const filtroSede = ref('all')
const tooltip = ref(null)
const tooltipY = ref(0)
const tooltipX = ref(0)

const hoy = computed(() => new Date().toISOString().split('T')[0])

const horaActual = computed(() => {
  return new Date().toLocaleTimeString('es-CO', { hour: '2-digit', minute: '2-digit' })
})

const vistaLabel = computed(() => {
  const m = { hoy: 'hoy', semana: 'últimos 7 días', mes: 'últimos 30 días' }
  return m[vista.value] || ''
})

const daysForVista = computed(() => {
  if (vista.value === 'hoy') return 1
  if (vista.value === 'semana') return 7
  return 30
})

const historialFiltrado = computed(() => {
  const cutoff = new Date()
  cutoff.setDate(cutoff.getDate() - daysForVista.value + 1)
  cutoff.setHours(0, 0, 0, 0)

  return (store.state.historial || []).filter(item => {
    if (!item.date && !item.created_at) return false
    const d = new Date((item.date || item.created_at) + (item.date ? 'T12:00:00' : ''))
    if (d < cutoff) return false
    const section = item.section || ''
    if (filtroSede.value !== 'all') {
      const room = store.state.rooms?.find(r => r.name === item.room_name)
      if (!room || room.sede !== filtroSede.value) return false
    }
    return true
  })
})

const columnas = computed(() => {
  if (vista.value === 'hoy') {
    const now = new Date()
    const cols = []
    for (let h = 0; h < 24; h++) {
      const label = String(h).padStart(2, '0') + ':00'
      cols.push({ key: 'h' + h, label, isNow: h === now.getHours() })
    }
    return cols
  }
  const days = daysForVista.value
  const cols = []
  const now = new Date()
  for (let i = days - 1; i >= 0; i--) {
    const d = new Date(now)
    d.setDate(d.getDate() - i)
    const key = d.toISOString().split('T')[0]
    const label = d.toLocaleDateString('es-CO', { day: 'numeric', month: 'short' })
    cols.push({ key, label, isNow: key === hoy.value })
  }
  return cols
})

const pisosOrdenados = ['Piso 9', 'Piso 8', 'Piso 7', 'Piso 6', 'Piso 5', 'Piso 2', 'Piso 1']

const extraerPiso = (section) => {
  const m = (section || '').match(/(Piso \d+)/i)
  return m ? m[1] : null
}

const filas = computed(() => {
  const map = {}
  pisosOrdenados.forEach(p => {
    map[p] = { cells: {}, detail: {} }
    columnas.value.forEach(c => {
      map[p].cells[c.key] = 0
      map[p].detail[c.key] = []
    })
  })

  historialFiltrado.value.forEach(item => {
    const piso = extraerPiso(item.section || '')
    if (!piso || !map[piso]) return
    let colKey
    if (vista.value === 'hoy') {
      const d = new Date(item.created_at || item.date)
      colKey = 'h' + d.getHours()
    } else {
      colKey = item.date || (item.created_at ? new Date(item.created_at).toISOString().split('T')[0] : null)
    }
    if (!colKey || !map[piso].cells.hasOwnProperty(colKey)) return
    map[piso].cells[colKey]++
    map[piso].detail[colKey].push(item)
  })

  return pisosOrdenados
    .filter(p => Object.values(map[p].cells).some(v => v > 0) || p === pisosOrdenados.find(x => map[x]))
    .map(piso => ({
      piso,
      cells: map[piso].cells,
      detail: map[piso].detail,
    }))
})

const maxIntensidad = computed(() => {
  let max = 1
  filas.value.forEach(row => {
    Object.values(row.cells).forEach(v => { if (v > max) max = v })
  })
  return max
})

const totalPorColumna = computed(() => {
  const totals = {}
  columnas.value.forEach(c => { totals[c.key] = 0 })
  filas.value.forEach(row => {
    Object.entries(row.cells).forEach(([key, val]) => {
      totals[key] = (totals[key] || 0) + val
    })
  })
  return totals
})

const colorForIntensity = (val, max) => {
  if (!val || val === 0) return '#f1f5f9'
  const t = Math.min(val / max, 1)
  const r = Math.round(59 + (239 - 59) * t)
  const g = Math.round(130 + (68 - 130) * t)
  const b = Math.round(246 - 246 * t)
  return `rgb(${r},${g},${b})`
}

const totalActividad = computed(() => historialFiltrado.value.length)
const totalFunciona = computed(() => historialFiltrado.value.filter(h => h.nuevo === 'funciona').length)
const totalNoFunciona = computed(() => historialFiltrado.value.filter(h => h.nuevo === 'no-funciona').length)
const totalAislado = computed(() => historialFiltrado.value.filter(h => h.nuevo === 'aislado').length)

const ultimosCambios = computed(() => {
  return [...(store.state.historial || [])]
    .sort((a, b) => new Date(b.created_at || b.date) - new Date(a.created_at || a.date))
    .slice(0, 10)
})

const estadoLabel = (e) => {
  const m = { funciona: 'Funciona', 'no-funciona': 'No funciona', aislado: 'Aislado', 'no-hay': 'No hay' }
  return m[e] || e || '—'
}

const formatTime = (ts) => {
  if (!ts) return ''
  const d = new Date(ts)
  if (isNaN(d.getTime())) return ts
  return d.toLocaleTimeString('es-CO', { hour: '2-digit', minute: '2-digit' })
}

document.addEventListener('mousemove', (e) => {
  if (!tooltip.value) return
  tooltipX.value = e.clientX + 12
  tooltipY.value = e.clientY + 12
})
</script>

<style scoped>
.mc-wrap {
  padding: 24px 28px;
}

.mc-hero {
  display: flex;
  align-items: center;
  gap: 14px;
  margin-bottom: 20px;
  animation: f 0.4s ease backwards;
}
.mc-hero-icon {
  width: 48px;
  height: 48px;
  border-radius: 14px;
  background: linear-gradient(135deg, #fef3c7, #fde68a);
  color: #d97706;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.mc-hero-title {
  font-size: 1.3rem;
  font-weight: 800;
  color: #0f172a;
  margin: 0;
}
.mc-hero-desc {
  font-size: 0.85rem;
  color: #64748b;
  margin: 2px 0 0;
}

.mc-kpis {
  display: grid;
  grid-template-columns: repeat(5, 1fr);
  gap: 10px;
  margin-bottom: 16px;
  animation: f 0.4s 0.05s ease backwards;
}
.mc-kpi {
  background: #fff;
  border: 1px solid #e2e8f0;
  border-radius: 14px;
  padding: 16px 12px;
  text-align: center;
  box-shadow: 0 2px 8px rgba(0,0,0,0.03);
  transition: all 0.2s;
}
.mc-kpi:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(0,0,0,0.06);
}
.mc-kpi-num {
  display: block;
  font-size: 1.6rem;
  font-weight: 800;
  color: #0f172a;
  line-height: 1;
  margin-bottom: 4px;
}
.mc-kpi-label {
  font-size: 0.62rem;
  color: #94a3b8;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.04em;
}

.mc-filters {
  display: flex;
  align-items: center;
  gap: 14px;
  margin-bottom: 16px;
  flex-wrap: wrap;
  background: #fff;
  border: 1.5px solid #e2e8f0;
  border-top: 3px solid #0D2D6B;
  border-radius: 14px;
  padding: 14px 20px;
  box-shadow: 0 2px 12px rgba(13,45,107,0.06);
  animation: f 0.4s 0.08s ease backwards;
}
.mc-chips {
  display: flex;
  gap: 4px;
}
.mc-chip {
  padding: 5px 12px;
  border: 1px solid #e2e8f0;
  border-radius: 100px;
  background: #fff;
  font-size: 0.72rem;
  font-weight: 600;
  color: #64748b;
  cursor: pointer;
  transition: all 0.15s;
  font-family: inherit;
}
.mc-chip:hover {
  border-color: #94a3b8;
  background: #f8fafc;
}
.mc-chip.active {
  background: #1e3a5f;
  color: #fff;
  border-color: #1e3a5f;
}
.mc-legend {
  display: flex;
  align-items: center;
  gap: 3px;
  margin-left: auto;
}
.mc-legend-label {
  font-size: 0.6rem;
  font-weight: 600;
  color: #94a3b8;
  text-transform: uppercase;
}
.mc-legend-cell {
  width: 16px;
  height: 12px;
  border-radius: 3px;
}

.mc-heatmap-card {
  background: #fff;
  border: 1px solid #e2e8f0;
  border-radius: 14px;
  overflow: hidden;
  box-shadow: 0 2px 10px rgba(0,0,0,0.04);
  margin-bottom: 20px;
  animation: f 0.4s 0.1s ease backwards;
}
.mc-heatmap-scroll {
  overflow-x: auto;
}
.mc-heatmap {
  width: 100%;
  border-collapse: collapse;
  font-size: 0.72rem;
}
.mc-heatmap th,
.mc-heatmap td {
  padding: 6px 4px;
  text-align: center;
  white-space: nowrap;
}
.mc-heatmap thead th {
  background: #f8fafc;
  border-bottom: 1px solid #e2e8f0;
  font-size: 0.6rem;
  font-weight: 700;
  color: #64748b;
  text-transform: uppercase;
  letter-spacing: 0.03em;
  position: sticky;
  top: 0;
}
.mc-hm-piso {
  font-size: 0.75rem !important;
  font-weight: 700 !important;
  color: #0f172a !important;
  text-align: left !important;
  padding: 8px 12px !important;
  min-width: 70px;
  background: #fff !important;
}
.mc-hm-col {
  min-width: 32px;
}
.mc-hm-now {
  background: #eff6ff !important;
  color: #3b82f6 !important;
}
.mc-hm-cell {
  cursor: pointer;
  transition: all 0.15s;
  position: relative;
  min-width: 32px;
  border: 1px solid #fff;
}
.mc-hm-cell:hover {
  transform: scale(1.15);
  z-index: 2;
  box-shadow: 0 4px 12px rgba(0,0,0,0.15);
  border-radius: 4px;
}
.mc-hm-val {
  font-size: 0.65rem;
  font-weight: 700;
  color: #475569;
  mix-blend-mode: multiply;
}
.mc-hm-total-row td {
  border-top: 2px solid #e2e8f0;
  background: #f8fafc;
  font-weight: 700;
}
.mc-hm-total {
  color: #0f172a;
}

.mc-tooltip {
  position: fixed;
  background: #fff;
  border: 1px solid #cbd5e1;
  border-radius: 10px;
  padding: 10px 12px 10px 14px;
  min-width: 180px;
  max-width: 260px;
  box-shadow: 0 0 0 3px rgba(99,102,241,0.12), 0 12px 40px rgba(0,0,0,0.14);
  z-index: 2000;
  pointer-events: none;
  font-size: 0.72rem;
  border-left: 3px solid #6366f1;
  animation: tipPop 0.2s ease-out;
}
.mc-tooltip strong {
  display: block;
  font-size: 0.78rem;
  color: #0f172a;
  margin-bottom: 4px;
}
.mc-tt-info {
  font-size: 0.65rem;
  color: #94a3b8;
  margin-bottom: 6px;
}
.mc-tt-item {
  display: flex;
  align-items: center;
  gap: 6px;
  padding: 2px 0;
  border-bottom: 1px solid #f1f5f9;
}
.mc-tt-item:last-child {
  border-bottom: none;
}
.mc-tt-room {
  flex: 1;
  font-weight: 500;
  color: #334155;
  font-size: 0.7rem;
}
.mc-tt-chip {
  font-size: 0.58rem;
  font-weight: 700;
  padding: 2px 8px;
  border-radius: 100px;
  text-transform: uppercase;
  letter-spacing: 0.02em;
  flex-shrink: 0;
}
.mc-tt-chip.funciona { background: #dcfce7; color: #16a34a; }
.mc-tt-chip.no-funciona { background: #fef2f2; color: #dc2626; }
.mc-tt-chip.aislado { background: #fffbeb; color: #d97706; }
.mc-tt-chip.no-hay { background: #f1f5f9; color: #64748b; }
.mc-tt-more {
  font-size: 0.62rem;
  color: #94a3b8;
  margin-top: 4px;
  text-align: center;
}

.mc-live {
  background: #fff;
  border: 1px solid #e2e8f0;
  border-radius: 14px;
  overflow: hidden;
  box-shadow: 0 2px 10px rgba(0,0,0,0.04);
  animation: f 0.4s 0.12s ease backwards;
}
.mc-live-header {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 12px 16px;
  background: #f8fafc;
  border-bottom: 1px solid #e2e8f0;
}
.mc-live-dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: #22c55e;
  animation: pulse 1.5s infinite;
}
.mc-live-header h3 {
  margin: 0;
  font-size: 0.82rem;
  font-weight: 700;
  color: #0f172a;
}
.mc-live-list {
  max-height: 360px;
  overflow-y: auto;
}
.mc-live-item {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 8px 16px;
  border-bottom: 1px solid #f1f5f9;
  font-size: 0.72rem;
  transition: background 0.1s;
}
.mc-live-item:hover {
  background: #fafbfc;
}
.mc-live-time {
  font-family: ui-monospace, monospace;
  font-size: 0.65rem;
  color: #94a3b8;
  min-width: 48px;
}
.mc-live-room {
  font-weight: 600;
  color: #0f172a;
}
.mc-live-section {
  color: #94a3b8;
  font-size: 0.65rem;
}
.mc-live-user {
  margin-left: auto;
  font-size: 0.62rem;
  color: #cbd5e1;
}
.mc-live-empty {
  padding: 24px;
  text-align: center;
  color: #94a3b8;
  font-size: 0.8rem;
}

@keyframes f {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}
@keyframes pulse {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.3; }
}
@keyframes tipPop { from { opacity: 0; transform: scale(0.92); } to { opacity: 1; transform: scale(1); } }

@media (max-width: 768px) {
  .mc-wrap { padding: 10px 12px; }
  .mc-hero { margin-bottom: 10px; }
  .mc-hero-title { font-size: 1.1rem; }
  .mc-hero-desc { font-size: 0.72rem; }
  .mc-kpis { gap: 8px; margin-bottom: 12px; flex-wrap: wrap; }
  .mc-kpi { min-width: calc(50% - 4px); flex: 1 0 calc(50% - 4px); padding: 10px 12px; border-radius: 12px; }
  .mc-kpi-num { font-size: 1.3rem; }
  .mc-kpi-label { font-size: 0.58rem; }
  .mc-filters { padding: 10px 14px; gap: 8px; }
  .mc-chips { flex-wrap: wrap; gap: 4px; }
  .mc-legend { margin-left: 0; order: 10; width: 100%; justify-content: center; }
  .mc-heatmap-card { margin-bottom: 12px; }
  .mc-heatmap th, .mc-heatmap td { padding: 4px 3px; font-size: 0.6rem; }
}
@media (max-width: 480px) {
  .mc-kpi-num { font-size: 1.1rem; }
  .mc-kpi { padding: 8px 10px; }
  .mc-hm-piso { font-size: 0.58rem; }
}
</style>
