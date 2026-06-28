<template>
  <div class="rep-wrap">
    <div class="rep-hero">
      <div class="rep-hero-icon">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
      </div>
      <div>
        <h2 class="rep-hero-title">Reportes</h2>
        <p class="rep-hero-desc">Generá informes del estado de las habitaciones</p>
      </div>
    </div>

    <div class="rep-filters">
      <div class="rep-f-group">
        <label class="rep-label">Desde</label>
        <input type="date" class="rep-input" v-model="fechaDesde" />
      </div>
      <div class="rep-f-group">
        <label class="rep-label">Hasta</label>
        <input type="date" class="rep-input" v-model="fechaHasta" />
      </div>
      <div class="rep-f-group">
        <label class="rep-label">Sede</label>
        <select class="rep-input" v-model="filtroSede">
          <option value="all">Todas</option>
          <option value="torre">Torre</option>
          <option value="urgencias">Urgencias</option>
        </select>
      </div>
      <div class="rep-f-group">
        <label class="rep-label">Sección</label>
        <select class="rep-input" v-model="filtroSeccion">
          <option value="all">Todas</option>
          <option v-for="s in seccionesUnicas" :key="s" :value="s">{{ s }}</option>
        </select>
      </div>
    </div>

    <Transition name="fad">
      <div v-if="roomsFiltrados.length" class="rep-result">
        <div class="rep-result-header">
          <h3>Reporte {{ fechaDesde || '—' }} al {{ fechaHasta || '—' }}</h3>
          <div class="rep-badges">
            <span class="rep-badge">{{ sedeLabel }}</span>
            <span class="rep-badge">{{ seccionLabel }}</span>
          </div>
        </div>

        <div class="rep-stats">
          <div class="rep-stat">
            <span class="rep-stat-n">{{ totalHabs }}</span>
            <span class="rep-stat-l">Habitaciones</span>
          </div>
          <div class="rep-stat">
            <span class="rep-stat-n" style="color:#16a34a">{{ totalFn }}</span>
            <span class="rep-stat-l">Funcionan</span>
          </div>
          <div class="rep-stat">
            <span class="rep-stat-n" style="color:#dc2626">{{ totalNf }}</span>
            <span class="rep-stat-l">No funcionan</span>
          </div>
          <div class="rep-stat">
            <span class="rep-stat-n" style="color:#d97706">{{ totalAi }}</span>
            <span class="rep-stat-l">Aislados</span>
          </div>
          <div class="rep-stat">
            <span class="rep-stat-n" style="color:#6b7280">{{ totalNh }}</span>
            <span class="rep-stat-l">No hay</span>
          </div>
          <div class="rep-stat">
            <span class="rep-stat-n" style="color:#94a3b8">{{ totalSr }}</span>
            <span class="rep-stat-l">Sin revisar</span>
          </div>
        </div>

        <div class="rep-chart">
          <div class="rep-chart-bar" v-for="e in chartData" :key="e.label">
            <span class="rep-chart-l">{{ e.label }}</span>
            <div class="rep-chart-track">
              <div class="rep-chart-fill" :style="{ width: e.pct + '%', background: e.color }"></div>
            </div>
            <span class="rep-chart-n">{{ e.count }}</span>
          </div>
        </div>

        <div class="rep-table">
          <div class="rep-t-header">
            <span>Sección</span>
            <span>Total</span>
            <span>Funciona</span>
            <span>No funciona</span>
            <span>Aislado</span>
            <span>No hay</span>
            <span>Sin revisar</span>
            <span>% Salud</span>
          </div>
          <div class="rep-t-row" v-for="row in reporteSecciones" :key="row.seccion">
            <span class="rep-t-sec">{{ row.seccion }}</span>
            <span class="rep-t-val">{{ row.total }}</span>
            <span class="rep-t-val" style="color:#16a34a">{{ row.fn }}</span>
            <span class="rep-t-val" style="color:#dc2626">{{ row.nf }}</span>
            <span class="rep-t-val" style="color:#d97706">{{ row.ai }}</span>
            <span class="rep-t-val" style="color:#6b7280">{{ row.nh }}</span>
            <span class="rep-t-val" style="color:#94a3b8">{{ row.sr }}</span>
            <span class="rep-t-val">
              <span :class="['rep-pct', row.salud > 80 ? 'pct-buena' : row.salud > 50 ? 'pct-media' : 'pct-mala']">{{ row.salud }}%</span>
            </span>
          </div>
        </div>

        <div class="rep-footer">
          <span class="rep-fecha">Generado: {{ ahora }}</span>
          <div class="rep-actions">
            <button class="rep-btn rep-btn-sec" @click="exportarExcel">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="8" y1="16" x2="8" y2="16"/><line x1="8" y1="12" x2="8" y2="12"/><line x1="8" y1="8" x2="8" y2="8"/></svg>
              Exportar Excel
            </button>
            <button class="rep-btn rep-btn-sec" @click="descargarPDF">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
              Descargar PDF
            </button>
            <button class="rep-btn rep-btn-sec" @click="imprimir">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
              Imprimir
            </button>
          </div>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useStore } from '../store'
import { api } from '../api'
import * as XLSX from 'xlsx'

const store = useStore()

const fechaDesde = ref('')
const fechaHasta = ref('')
const filtroSede = ref('all')
const filtroSeccion = ref('all')

const rooms = computed(() => store.state.rooms)

const seccionesUnicas = computed(() => {
  const s = new Set(rooms.value.map(r => r.section).filter(Boolean))
  return Array.from(s).sort()
})

const sedeLabel = computed(() => {
  if (filtroSede.value === 'all') return 'Todas las sedes'
  return filtroSede.value === 'torre' ? 'Torre' : 'Urgencias'
})
const seccionLabel = computed(() => {
  if (filtroSeccion.value === 'all') return 'Todas las secciones'
  return filtroSeccion.value
})

const roomsFiltrados = computed(() => {
  const roomsList = rooms.value.filter(r => {
    if (filtroSede.value !== 'all' && r.sede !== filtroSede.value) return false
    if (filtroSeccion.value !== 'all' && r.section !== filtroSeccion.value) return false
    return true
  })

  if (!fechaDesde.value || !fechaHasta.value) return roomsList

  return roomsList.map(r => {
    const entries = store.state.historial.filter(h =>
      h.room_name === r.name &&
      h.date >= fechaDesde.value &&
      h.date <= fechaHasta.value
    )
    if (entries.length > 0) {
      entries.sort((a, b) => b.id - a.id)
      return { ...r, estado: entries[0].nuevo, timestamp: entries[0].date }
    }
    return { ...r, estado: null, timestamp: null }
  })
})

const ahora = computed(() => {
  roomsFiltrados.value
  return new Date().toLocaleString('es-CO', { dateStyle: 'long', timeStyle: 'short' })
})

const totalHabs = computed(() => roomsFiltrados.value.length)
const totalFn = computed(() => roomsFiltrados.value.filter(r => r.estado === 'funciona').length)
const totalNf = computed(() => roomsFiltrados.value.filter(r => r.estado === 'no-funciona').length)
const totalAi = computed(() => roomsFiltrados.value.filter(r => r.estado === 'aislado').length)
const totalNh = computed(() => roomsFiltrados.value.filter(r => r.estado === 'no-hay').length)
const totalSr = computed(() => roomsFiltrados.value.filter(r => !r.timestamp).length)

const chartData = computed(() => {
  const t = totalHabs.value || 1
  return [
    { label: 'Funciona', count: totalFn.value, pct: Math.round(totalFn.value / t * 100), color: '#22c55e' },
    { label: 'No funciona', count: totalNf.value, pct: Math.round(totalNf.value / t * 100), color: '#ef4444' },
    { label: 'Aislado', count: totalAi.value, pct: Math.round(totalAi.value / t * 100), color: '#f59e0b' },
    { label: 'No hay', count: totalNh.value, pct: Math.round(totalNh.value / t * 100), color: '#94a3b8' },
    { label: 'Sin revisar', count: totalSr.value, pct: Math.round(totalSr.value / t * 100), color: '#e2e8f0' },
  ]
})

const reporteSecciones = computed(() => {
  const order = ['Piso 9', 'Piso 8', 'Piso 7', 'Piso 6', 'Piso 5', 'Piso 2', 'Piso 1']
  const set = {}
  roomsFiltrados.value.forEach(r => {
    const sec = r.section || 'Sin sección'
    if (!set[sec]) set[sec] = { seccion: sec, total: 0, fn: 0, nf: 0, ai: 0, nh: 0, sr: 0 }
    set[sec].total++
    if (!r.timestamp) set[sec].sr++
    else if (r.estado === 'funciona') set[sec].fn++
    else if (r.estado === 'no-funciona') set[sec].nf++
    else if (r.estado === 'aislado') set[sec].ai++
    else if (r.estado === 'no-hay') set[sec].nh++
    else set[sec].sr++
  })
  const arr = Object.values(set)
  const sorted = []
  order.forEach(o => {
    const i = arr.findIndex(x => x.seccion === o)
    if (i >= 0) { sorted.push(arr[i]); arr.splice(i, 1) }
  })
  arr.sort((a, b) => a.seccion.localeCompare(b.seccion)).forEach(x => sorted.push(x))
  return sorted.map(s => ({
    ...s,
    salud: s.total ? Math.round((s.fn / s.total) * 100) : 0
  }))
})

const imprimir = () => {
  window.print()
}

const exportarExcel = () => {
  const datos = [
    ['Sección', 'Total', 'Funciona', 'No funciona', 'Aislado', 'No hay', 'Sin revisar', '% Salud'],
    ...reporteSecciones.value.map(r => [r.seccion, r.total, r.fn, r.nf, r.ai, r.nh, r.sr, r.salud])
  ]
  const wb = XLSX.utils.book_new()
  const ws = XLSX.utils.aoa_to_sheet(datos)
  XLSX.utils.book_append_sheet(wb, ws, 'Reporte')
  XLSX.writeFile(wb, `Reporte_${fechaDesde.value || 'inicio'}_${fechaHasta.value || 'fin'}.xlsx`)
}

const descargarPDF = async () => {
  try {
    const filters = {}
    if (fechaDesde.value) filters.date_from = fechaDesde.value
    if (fechaHasta.value) filters.date_to = fechaHasta.value
    if (filtroSede.value !== 'all') filters.sede = filtroSede.value
    if (filtroSeccion.value !== 'all') filters.section = filtroSeccion.value
    await api.downloadHistorialPdf(filters)
  } catch (error) {
    store.showToast('Error al descargar PDF', 'error')
  }
}
</script>

<style scoped>
.rep-wrap {
  padding: 24px 28px;
}
.rep-hero {
  display: flex;
  align-items: center;
  gap: 14px;
  margin-bottom: 20px;
  animation: f 0.4s ease backwards;
}
.rep-hero-icon {
  width: 48px;
  height: 48px;
  border-radius: 14px;
  background: linear-gradient(135deg, #dbeafe, #bfdbfe);
  color: #1d4ed8;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.rep-hero-title {
  font-size: 1.3rem;
  font-weight: 800;
  color: #0f172a;
  margin: 0;
}
.rep-hero-desc {
  font-size: 0.85rem;
  color: #64748b;
  margin: 2px 0 0;
}
.rep-filters {
  display: flex;
  align-items: flex-end;
  gap: 12px;
  flex-wrap: wrap;
  background: #fff;
  border: 1px solid #e2e8f0;
  border-radius: 14px;
  padding: 14px 18px;
  margin-bottom: 20px;
  animation: f 0.4s 0.05s ease backwards;
}
.rep-f-group {
  display: flex;
  flex-direction: column;
  gap: 3px;
}
.rep-label {
  font-size: 0.65rem;
  font-weight: 700;
  color: #64748b;
  text-transform: uppercase;
  letter-spacing: 0.03em;
}
.rep-input {
  padding: 6px 10px;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  font-size: 0.82rem;
  font-family: inherit;
  color: #0f172a;
  background: #f8fafc;
  transition: border 0.15s;
  min-width: 140px;
}
.rep-input:focus {
  outline: none;
  border-color: #3b82f6;
  background: #fff;
}
.rep-btn {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  padding: 7px 16px;
  border: none;
  border-radius: 8px;
  background: #1e3a5f;
  color: #fff;
  font-size: 0.82rem;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.15s;
  font-family: inherit;
  white-space: nowrap;
}
.rep-btn:hover {
  background: #2a4a7a;
  transform: translateY(-1px);
}
.rep-btn-sec {
  background: #f1f5f9;
  color: #475569;
}
.rep-btn-sec:hover {
  background: #e2e8f0;
  color: #0f172a;
}
.rep-actions {
  display: flex;
  gap: 8px;
}
.rep-result {
  animation: f 0.4s ease backwards;
}
.rep-result-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 10px;
  margin-bottom: 16px;
}
.rep-result-header h3 {
  margin: 0;
  font-size: 1rem;
  font-weight: 700;
  color: #0f172a;
}
.rep-badges {
  display: flex;
  gap: 6px;
}
.rep-badge {
  font-size: 0.62rem;
  font-weight: 700;
  padding: 3px 10px;
  border-radius: 100px;
  background: #f1f5f9;
  color: #475569;
}
.rep-stats {
  display: grid;
  grid-template-columns: repeat(6, 1fr);
  gap: 10px;
  margin-bottom: 18px;
}
.rep-stat {
  background: #fff;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  padding: 12px 8px;
  text-align: center;
  box-shadow: 0 2px 6px rgba(0,0,0,0.03);
}
.rep-stat-n {
  display: block;
  font-size: 1.3rem;
  font-weight: 800;
  color: #0f172a;
  line-height: 1;
}
.rep-stat-l {
  font-size: 0.6rem;
  color: #94a3b8;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.03em;
  margin-top: 4px;
}
.rep-chart {
  display: flex;
  flex-direction: column;
  gap: 6px;
  margin-bottom: 20px;
  background: #fff;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  padding: 14px 18px;
}
.rep-chart-bar {
  display: flex;
  align-items: center;
  gap: 10px;
}
.rep-chart-l {
  font-size: 0.72rem;
  font-weight: 600;
  color: #475569;
  min-width: 80px;
}
.rep-chart-track {
  flex: 1;
  height: 8px;
  background: #f1f5f9;
  border-radius: 6px;
  overflow: hidden;
}
.rep-chart-fill {
  height: 100%;
  border-radius: 6px;
  transition: width 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
}
.rep-chart-n {
  font-size: 0.72rem;
  font-weight: 700;
  color: #64748b;
  min-width: 24px;
  text-align: right;
}
.rep-table {
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  overflow: hidden;
  margin-bottom: 16px;
  background: #fff;
}
.rep-t-header {
  display: grid;
  grid-template-columns: 1fr repeat(7, 64px);
  gap: 0;
  padding: 8px 14px;
  background: #f8fafc;
  border-bottom: 1px solid #e2e8f0;
  font-size: 0.6rem;
  font-weight: 700;
  color: #64748b;
  text-transform: uppercase;
  letter-spacing: 0.03em;
}
.rep-t-header span {
  text-align: center;
}
.rep-t-header span:first-child {
  text-align: left;
}
.rep-t-row {
  display: grid;
  grid-template-columns: 1fr repeat(7, 64px);
  gap: 0;
  padding: 7px 14px;
  border-bottom: 1px solid #f1f5f9;
  font-size: 0.78rem;
  align-items: center;
  transition: background 0.1s;
}
.rep-t-row:hover {
  background: #fafbfc;
}
.rep-t-row:last-child {
  border-bottom: none;
}
.rep-t-sec {
  font-weight: 700;
  color: #0f172a;
}
.rep-t-val {
  text-align: center;
  font-weight: 600;
  color: #475569;
}
.rep-pct {
  display: inline-block;
  padding: 1px 8px;
  border-radius: 100px;
  font-size: 0.7rem;
  font-weight: 700;
}
.pct-buena { background: #dcfce7; color: #15803d; }
.pct-media { background: #fef3c7; color: #b45309; }
.pct-mala { background: #fef2f2; color: #b91c1c; }
.rep-footer {
  display: flex;
  align-items: center;
  justify-content: space-between;
}
.rep-fecha {
  font-size: 0.72rem;
  color: #94a3b8;
}

.fad-enter-active { transition: all 0.35s ease; }
.fad-enter-from { opacity: 0; transform: translateY(16px); }

@keyframes f {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

@media print {
  .rep-hero, .rep-filters, .rep-footer .rep-btn { display: none; }
  .rep-wrap { padding: 0; }
}
</style>
