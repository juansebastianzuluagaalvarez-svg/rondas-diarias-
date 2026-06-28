<template>
  <div class="analisis">
    <div class="analisis-toolbar">
      <div class="view-toggle-group">
        <button :class="['view-toggle-btn', vista === 'general' ? 'active' : '']" @click="vista = 'general'">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 20V10"/><path d="M12 20V4"/><path d="M6 20v-6"/></svg>
          General
        </button>
        <button :class="['view-toggle-btn', vista === 'por-piso' ? 'active' : '']" @click="vista = 'por-piso'">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
          Por piso
        </button>
      </div>
      <button class="btn btn-primary btn-seleccionar" @click="abrirModal">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 21l-4.35-4.35M11 4a7 7 0 1 0 0 14 7 7 0 0 0 0-14z"/><path d="M16 5l3 3"/><path d="M19 2l3 3"/></svg>
        Seleccionar habitación
      </button>
    </div>

    <div v-if="habitacionSeleccionada" class="room-focus-header">
      <div class="room-focus-top">
        <div class="room-focus-icon">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
        </div>
        <div class="room-focus-info">
          <div class="room-focus-name">{{ habitacionSeleccionada }}</div>
          <div class="room-focus-meta" v-if="habitacionData">{{ habitacionData.section || '—' }} · {{ habitacionData.type === 'llamados' ? 'Llamado' : 'Baño' }}</div>
        </div>
        <span v-if="habitacionData" :class="['room-focus-chip', habitacionData.estado]">{{ estadoLabel(habitacionData.estado) }}</span>
      </div>
      <button class="btn-trash room-focus-close" @click="limpiarSeleccion" title="Limpiar selección">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 6L6 18"/><path d="M6 6l12 12"/></svg>
      </button>
    </div>

    <div v-if="habitacionSeleccionada" class="timeline-wrap">
      <div class="timeline-card">
        <template v-if="habitacionSeleccionada">
          <div class="timeline-title">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            <span>Estado diario — {{ habitacionSeleccionada }}</span>
            <span class="timeline-mes">{{ mesNombre }}</span>
            <button class="btn-week-toggle" @click="toggleVistaTimeline">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/></svg>
              {{ vistaTimeline === 'month' ? 'Semana' : 'Mes' }}
            </button>
            <button class="btn-resumen" @click="abrirResumen">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
              Resumen
            </button>
          </div>
          <div :class="['timeline-grid', vistaTimeline === 'week' ? 'timeline-grid-week' : '']">
            <div v-for="d in timelineData" :key="d.key" :class="['timeline-col', { 'timeline-other-month': d.isOtherMonth }]">
              <div :class="['timeline-state', d.state ? 'state-' + d.state : '']" @click="abrirDetalle(d)">
                {{ d.label }}
                <div class="timeline-tooltip">
                  <div class="tt-header">Día {{ d.day }} — <span :class="'tt-state-' + d.state">{{ estadoTextoCorto(d.state) }}</span></div>
                  <div v-if="d.detalle?.hora" class="tt-row">Hora: {{ d.detalle.hora.includes('T') ? d.detalle.hora.split('T')[1]?.split('.')[0] : d.detalle.hora }}</div>
                  <div v-if="d.detalle?.anterior" class="tt-row">Anterior: {{ d.detalle.anterior }}</div>
                  <div v-if="d.detalle?.observacion" class="tt-row tt-obs">{{ d.detalle.observacion }}</div>
                </div>
              </div>
            </div>
          </div>
          <div class="timeline-legend">
            <span><span class="legend-dot state-F"></span> F = Funciona</span>
            <span><span class="legend-dot state-M"></span> M = No funciona</span>
            <span><span class="legend-dot state-A"></span> A = Aislado</span>
            <span><span class="legend-dot state-NH"></span> NH = No hay</span>
            <span><span style="background:#e2e8f0;width:10px;height:10px;border-radius:2px;display:inline-block;margin-right:3px;"></span> — = Sin datos</span>
          </div>
        </template>
      </div>
    </div>

    <div :class="['modal-overlay', modalOpen ? 'open' : '']" @click.self="cerrarModal">
      <div class="modal analisis-modal">
        <div class="modal-header">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
          <span>Seleccionar habitación</span>
          <span class="modal-hab-count">{{ totalHabitaciones }} hab.</span>
          <button class="modal-close" @click="cerrarModal">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 6L6 18"/><path d="M6 6l12 12"/></svg>
          </button>
        </div>
        <div class="modal-filtros">
          <div class="toggle-group">
            <button :class="['toggle-btn', modalFiltroTipo === 'llamados' ? 'active' : '']" @click="modalFiltroTipo = 'llamados'">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 2L11 13"/><path d="M22 2l-7 20-4-9-9-4 20-7z"/></svg>
              Llamados
            </button>
            <button :class="['toggle-btn', modalFiltroTipo === 'baños' ? 'active' : '']" @click="modalFiltroTipo = 'baños'">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 8h16"/><path d="M6 12V8"/><path d="M10 12V8"/><path d="M14 12V8"/><path d="M18 12V8"/><path d="M4 16h16"/><path d="M2 20h20"/></svg>
              Baños
            </button>
          </div>
          <div class="filter-select-wrap">
            <span class="filter-label">Piso</span>
            <select class="filter-select" v-model="modalFiltroPiso">
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
        </div>
        <div class="modal-body">
          <div class="modal-busqueda">
            <svg class="modal-busqueda-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
            <input class="modal-busqueda-input" v-model="busqueda" placeholder="Buscar habitación..." />
          </div>
          <div v-for="(seccion, i) in modalSecciones" :key="i" class="modal-seccion">
            <div class="modal-seccion-titulo">
              {{ seccion.nombre }}
              <span class="modal-seccion-count">{{ seccion.rooms.length }}</span>
            </div>
            <div class="modal-rooms">
              <div v-for="room in seccion.rooms" :key="room.id" :class="['modal-room', room.estado]" @click="seleccionarHabitacion(room.name)">
                <span class="modal-room-name">{{ room.name }}</span>
                <span :class="['chip', room.estado]">{{ estadoLabel(room.estado) }}</span>
              </div>
            </div>
          </div>
          <div v-if="!modalSecciones.length" class="modal-empty">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="color:#cbd5e1"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            <span>No hay habitaciones con estos filtros</span>
          </div>
        </div>
      </div>
    </div>

    <div v-if="vista === 'general' && !habitacionSeleccionada" class="summary-dashboard">
      <div class="dashboard-grid">
        <div class="dashboard-card">
          <div class="dashboard-card-title">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"/><path d="M22 12A10 10 0 0 0 12 2v10z"/></svg>
            Distribución general
          </div>
          <div class="pie-container">
            <div class="pie-chart" :style="{ background: pieGradient }">
              <div class="pie-hole">{{ distribucionGeneral.total }}</div>
            </div>
            <div class="pie-legend">
              <div class="pie-legend-item">
                <span class="pie-dot" style="background:#059669"></span>
                <span class="pie-label">Funciona</span>
                <span class="pie-count">{{ distribucionGeneral.funciona }}</span>
              </div>
              <div class="pie-legend-item">
                <span class="pie-dot" style="background:#e11d48"></span>
                <span class="pie-label">No funciona</span>
                <span class="pie-count">{{ distribucionGeneral['no-funciona'] }}</span>
              </div>
              <div class="pie-legend-item">
                <span class="pie-dot" style="background:#ea580c"></span>
                <span class="pie-label">Aislado</span>
                <span class="pie-count">{{ distribucionGeneral.aislado }}</span>
              </div>
              <div class="pie-legend-item">
                <span class="pie-dot" style="background:#475569"></span>
                <span class="pie-label">Sin llamado</span>
                <span class="pie-count">{{ distribucionGeneral['no-hay'] }}</span>
              </div>
            </div>
          </div>
        </div>

        <div class="dashboard-card">
          <div class="dashboard-card-title">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="18" y="3" width="4" height="18"/><rect x="10" y="8" width="4" height="13"/><rect x="2" y="13" width="4" height="8"/></svg>
            Estado por piso
            <select class="piso-resumen-select" v-model="pisoResumen">
              <option value="all">Todos los pisos</option>
              <option v-for="p in ordenSecciones" :key="p" :value="p">{{ p }}</option>
            </select>
          </div>
          <div class="piso-bars">
            <div v-for="row in pisoBarsData" :key="row.piso" class="piso-bar-row">
              <span class="piso-bar-label">{{ row.piso }}</span>
              <div class="piso-bar-group">
                <div class="piso-bar-item" v-if="row.funciona > 0">
                  <div class="piso-bar-track">
                    <div class="piso-bar-fill" style="background: linear-gradient(90deg, #059669, #10b981)" :style="{ width: row.funcionaPct + '%' }"></div>
                  </div>
                  <span class="piso-bar-val">{{ row.funciona }}</span>
                </div>
                <div class="piso-bar-item" v-if="row['no-funciona'] > 0">
                  <div class="piso-bar-track">
                    <div class="piso-bar-fill" style="background: linear-gradient(90deg, #e11d48, #f43f5e)" :style="{ width: row.noFuncionaPct + '%' }"></div>
                  </div>
                  <span class="piso-bar-val">{{ row['no-funciona'] }}</span>
                </div>
                <div class="piso-bar-item" v-if="row.aislado > 0">
                  <div class="piso-bar-track">
                    <div class="piso-bar-fill" style="background: linear-gradient(90deg, #ea580c, #f97316)" :style="{ width: row.aisladoPct + '%' }"></div>
                  </div>
                  <span class="piso-bar-val">{{ row.aislado }}</span>
                </div>
                <div class="piso-bar-item" v-if="row['no-hay'] > 0">
                  <div class="piso-bar-track">
                    <div class="piso-bar-fill" style="background: linear-gradient(90deg, #475569, #64748b)" :style="{ width: row.noHayPct + '%' }"></div>
                  </div>
                  <span class="piso-bar-val">{{ row['no-hay'] }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="piso-summary">
        <h3 class="piso-summary-title">Resumen por Piso</h3>
        <div class="piso-grid">
          <div v-for="p in pisosData" :key="p.piso" class="piso-card-dash">
            <div class="piso-card-header">
              <span class="piso-card-name">{{ p.piso }}</span>
              <span class="piso-card-total">{{ p.total }} hab.</span>
            </div>
            <div class="piso-card-row">
              <span class="piso-card-label">Funciona</span>
              <span class="piso-card-num">{{ p.funciona }}<span class="piso-card-total">/{{ p.total }}</span></span>
            </div>
            <div class="piso-card-bar">
              <div class="piso-card-fill" :style="{ width: p.funcionaPct + '%' }"></div>
            </div>
            <div class="piso-card-row">
              <span class="piso-card-label">Sin revisar</span>
              <span class="piso-card-num piso-card-pending">{{ p.pendientes }}</span>
            </div>
            <div :class="['piso-card-health', p.healthClass]"></div>
          </div>
        </div>
      </div>
      <div class="alerts-section" v-if="alertas.length">
        <h3 class="piso-summary-title">Alertas</h3>
        <div class="alerts-list">
          <div v-for="(a, i) in alertas" :key="i" class="alert-card">
            <div class="alert-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            </div>
            <div class="alert-body">
              <strong>{{ a.piso }}</strong>
              <span>{{ a.total }} dispositivo{{ a.total !== 1 ? 's' : '' }} con problemas</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div v-if="vista === 'por-piso' && !habitacionSeleccionada" class="por-piso-section">
      <div class="por-piso-header">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        <select class="por-piso-select" v-model="pisoSeleccionado">
          <option value="">Seleccionar piso...</option>
          <option v-for="p in ordenSecciones" :key="p" :value="p">{{ p }}</option>
        </select>
      </div>
      <div v-if="pisoSeleccionado && pisoRooms.length" class="por-piso-content">
        <div class="por-piso-chart">
          <div class="por-piso-chart-title">Distribución — {{ pisoSeleccionado }}</div>
          <div class="por-piso-bars">
            <div class="por-piso-bar-row">
              <span class="por-piso-bar-label">Funciona</span>
              <div class="por-piso-bar-track">
                <div class="por-piso-bar-fill" style="background: linear-gradient(90deg, #059669, #10b981)" :style="{ width: pisoDistrPct('funciona') + '%' }"></div>
              </div>
              <span class="por-piso-bar-count">{{ pisoDistr('funciona') }}</span>
            </div>
            <div class="por-piso-bar-row">
              <span class="por-piso-bar-label">No funciona</span>
              <div class="por-piso-bar-track">
                <div class="por-piso-bar-fill" style="background: linear-gradient(90deg, #e11d48, #f43f5e)" :style="{ width: pisoDistrPct('no-funciona') + '%' }"></div>
              </div>
              <span class="por-piso-bar-count">{{ pisoDistr('no-funciona') }}</span>
            </div>
            <div class="por-piso-bar-row">
              <span class="por-piso-bar-label">Aislado</span>
              <div class="por-piso-bar-track">
                <div class="por-piso-bar-fill" style="background: linear-gradient(90deg, #ea580c, #f97316)" :style="{ width: pisoDistrPct('aislado') + '%' }"></div>
              </div>
              <span class="por-piso-bar-count">{{ pisoDistr('aislado') }}</span>
            </div>
            <div class="por-piso-bar-row">
              <span class="por-piso-bar-label">Sin llamado</span>
              <div class="por-piso-bar-track">
                <div class="por-piso-bar-fill" style="background: linear-gradient(90deg, #475569, #64748b)" :style="{ width: pisoDistrPct('no-hay') + '%' }"></div>
              </div>
              <span class="por-piso-bar-count">{{ pisoDistr('no-hay') }}</span>
            </div>
          </div>
        </div>
        <div class="por-piso-rooms">
          <div class="por-piso-rooms-title">Habitaciones / Baños</div>
          <div class="por-piso-rooms-grid">
            <div v-for="room in pisoRooms" :key="room.id" :class="['por-piso-room', room.estado]" @click="seleccionarHabitacion(room.name)">
              <span class="por-piso-room-name">{{ room.name }}</span>
              <span :class="['chip', room.estado]">{{ estadoLabel(room.estado) }}</span>
            </div>
          </div>
        </div>
      </div>
      <div v-else class="por-piso-empty">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="color:#cbd5e1"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <span>{{ pisoSeleccionado && !pisoRooms.length ? 'No hay habitaciones en este piso' : 'Seleccioná un piso para ver su detalle' }}</span>
      </div>
    </div>

    <div :class="['modal-overlay', detalleModalOpen ? 'open' : '']" @click.self="cerrarDetalleModal">
      <div class="resumen-card">
        <button class="resumen-close" @click="cerrarDetalleModal">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M18 6L6 18"/><path d="M6 6l12 12"/></svg>
        </button>

        <div class="resumen-top">
          <div class="resumen-room">
            <div class="resumen-room-icon">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2z"/><path d="M8 6h8"/><path d="M8 10h8"/><path d="M8 14h4"/></svg>
            </div>
            <div>
              <div class="resumen-room-sub">Habitación</div>
              <div class="resumen-room-num">{{ numeroHabitacion }}</div>
            </div>
          </div>
          <div :class="['resumen-chip', 'chip-' + (detalleDia?.state || 'NH')]">
            <span class="resumen-chip-letter">{{ detalleDia?.label || '—' }}</span>
            <span>{{ estadoTextoCorto(detalleDia?.state) || 'Sin datos' }}</span>
          </div>
        </div>

        <div class="resumen-mes">{{ mesNombre }}</div>

        <div class="resumen-chart">
          <div class="resumen-chart-title">Distribución del mes</div>
          <div class="resumen-chart-bars">
            <div class="resumen-chart-row">
              <span class="resumen-chart-label">Funciona</span>
              <div class="resumen-chart-track">
                <div class="resumen-chart-fill" style="background:#22c55e" :style="{ width: pctF + '%' }"></div>
              </div>
              <span class="resumen-chart-count">{{ estadosCount.F }}<span class="resumen-chart-pct"> ({{ pctF }}%)</span></span>
            </div>
            <div class="resumen-chart-row">
              <span class="resumen-chart-label">No funciona</span>
              <div class="resumen-chart-track">
                <div class="resumen-chart-fill" style="background:#ef4444" :style="{ width: pctM + '%' }"></div>
              </div>
              <span class="resumen-chart-count">{{ estadosCount.M }}<span class="resumen-chart-pct"> ({{ pctM }}%)</span></span>
            </div>
            <div class="resumen-chart-row">
              <span class="resumen-chart-label">Aislado</span>
              <div class="resumen-chart-track">
                <div class="resumen-chart-fill" style="background:#f59e0b" :style="{ width: pctA + '%' }"></div>
              </div>
              <span class="resumen-chart-count">{{ estadosCount.A }}<span class="resumen-chart-pct"> ({{ pctA }}%)</span></span>
            </div>
            <div class="resumen-chart-row">
              <span class="resumen-chart-label">Sin llamado</span>
              <div class="resumen-chart-track">
                <div class="resumen-chart-fill" style="background:#94a3b8" :style="{ width: pctNH + '%' }"></div>
              </div>
              <span class="resumen-chart-count">{{ estadosCount.NH }}<span class="resumen-chart-pct"> ({{ pctNH }}%)</span></span>
            </div>
          </div>
        </div>

        <div v-if="topObservaciones.length" class="resumen-topobs">
          <div class="resumen-topobs-text">{{ topObservaciones[0].text }}</div>
          <div class="resumen-topobs-footer">
            <span class="resumen-topobs-count">{{ topObservaciones[0].count }} veces</span>
            <span v-if="topObservaciones.length > 1" class="resumen-topobs-mas" @click="showAllObs = !showAllObs">
              {{ showAllObs ? 'Ocultar' : 'Ver todas' }} ({{ topObservaciones.length }})
            </span>
          </div>
          <div v-if="showAllObs" class="resumen-obs-tags" style="margin-top:8px">
            <span v-for="(obs, i) in topObservaciones.slice(1)" :key="i" class="resumen-obs-tag">
              {{ obs.text }}
              <span class="resumen-obs-count">{{ obs.count }}</span>
            </span>
          </div>
        </div>

        <div v-if="detalleDia?.detalle?.observacion" class="resumen-nota">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
          {{ detalleDia.detalle.observacion }}
        </div>

        <div v-if="!detalleDia?.detalle?.hora && !detalleDia?.detalle?.anterior" class="resumen-empty">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
          <span>Sin registros este día</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useStore } from '../store'

const store = useStore()

const rooms = computed(() => store.state.rooms)

const habitacionData = computed(() => {
  if (!habitacionSeleccionada.value) return null
  return rooms.value.find(r => r.name === habitacionSeleccionada.value) || null
})

const extraerPiso = (section) => {
  const m = section?.match(/(Piso \d+)/i)
  return m ? m[1] : section || 'Sin piso'
}

const pisosData = computed(() => {
  const map = {}
  rooms.value.forEach(r => {
    const piso = extraerPiso(r.section || '')
    if (!map[piso]) map[piso] = { piso, total: 0, funciona: 0, noFunciona: 0, aislado: 0, pendientes: 0 }
    map[piso].total++
    if (r.estado === 'funciona') map[piso].funciona++
    else if (r.estado === 'no-funciona') map[piso].noFunciona++
    else if (r.estado === 'aislado') map[piso].aislado++
    if (!r.timestamp) map[piso].pendientes++
  })
  const order = ['Piso 9', 'Piso 8', 'Piso 7', 'Piso 6', 'Piso 5', 'Piso 2', 'Piso 1']
  const arr = Object.values(map)
  const sorted = []
  order.forEach(o => {
    const idx = arr.findIndex(x => x.piso === o)
    if (idx >= 0) {
      const d = arr[idx]
      const pct = d.total ? Math.round(d.funciona / d.total * 100) : 0
      sorted.push({ ...d, funcionaPct: pct, healthClass: pct >= 80 ? 'health-green' : pct >= 50 ? 'health-yellow' : 'health-red' })
      arr.splice(idx, 1)
    }
  })
  arr.sort((a, b) => a.piso.localeCompare(b.piso)).forEach(x => {
    const pct = x.total ? Math.round(x.funciona / x.total * 100) : 0
    sorted.push({ ...x, funcionaPct: pct, healthClass: pct >= 80 ? 'health-green' : pct >= 50 ? 'health-yellow' : 'health-red' })
  })
  return sorted
})

const alertas = computed(() => {
  return pisosData.value
    .map(p => ({ piso: p.piso, total: p.noFunciona + p.aislado }))
    .filter(p => p.total > 0)
    .sort((a, b) => b.total - a.total)
    .slice(0, 3)
})

const busqueda = ref('')
const habitacionSeleccionada = ref(null)
const modalOpen = ref(false)
const modalFiltroTipo = ref('llamados')
const modalFiltroPiso = ref('all')

const limpiarSeleccion = () => {
  habitacionSeleccionada.value = null
  busqueda.value = ''
}

const abrirModal = () => { modalOpen.value = true }

const seleccionarHabitacion = (name) => {
  habitacionSeleccionada.value = name
  busqueda.value = name
  modalOpen.value = false
}

const cerrarModal = () => { modalOpen.value = false }

const detalleModalOpen = ref(false)
const detalleDia = ref(null)
const showAllObs = ref(false)

const abrirDetalle = (d) => {
  detalleDia.value = d
  detalleModalOpen.value = true
}

const abrirResumen = () => {
  if (detalleModalOpen.value) {
    cerrarDetalleModal()
  } else {
    if (!detalleDia.value) {
      detalleDia.value = { day: new Date().getDate(), state: null, label: '—', detalle: null }
    }
    detalleModalOpen.value = true
  }
}

const cerrarDetalleModal = () => {
  detalleModalOpen.value = false
  detalleDia.value = null
}

const estadoTexto = (s) => {
  if (!s) return ''
  const map = { 'funciona':'Funcionaba','no-funciona':'No funcionaba','aislado':'Aislado','no-hay':'Sin llamado' }
  return map[s] || s
}

const estadoTextoCorto = (s) => {
  if (!s) return ''
  const map = { 'F':'Funciona','M':'No funciona','A':'Aislado','NH':'No hay' }
  return map[s] || s
}

const numeroHabitacion = computed(() => {
  if (!habitacionSeleccionada.value) return '—'
  const m = habitacionSeleccionada.value.match(/\d+/)
  return m ? m[0] : habitacionSeleccionada.value
})

const getHistorialDia = (roomName, day) => {
  const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`
  return store.state.historial.filter(h => h.room_name === roomName && h.date === dateStr)
    .sort((a, b) => new Date(a.created_at || a.date) - new Date(b.created_at || b.date))
}

const pctF = computed(() => Math.round((estadosCount.value.F / daysInMonth) * 100) || 0)
const pctM = computed(() => Math.round((estadosCount.value.M / daysInMonth) * 100) || 0)
const pctA = computed(() => Math.round((estadosCount.value.A / daysInMonth) * 100) || 0)
const pctNH = computed(() => Math.round((estadosCount.value.NH / daysInMonth) * 100) || 0)

const prevMonth = computed(() => month === 0 ? 11 : month - 1)
const prevYear = computed(() => month === 0 ? year - 1 : year)

const historialRoomMes = computed(() => {
  if (!habitacionSeleccionada.value) return []
  return store.state.historial.filter(h => {
    const d = new Date(h.date + 'T12:00:00')
    return h.room_name === habitacionSeleccionada.value &&
      d.getFullYear() === year && d.getMonth() === month
  })
})

const historialRoomMesAnterior = computed(() => {
  if (!habitacionSeleccionada.value) return []
  return store.state.historial.filter(h => {
    const d = new Date(h.date + 'T12:00:00')
    return h.room_name === habitacionSeleccionada.value &&
      d.getFullYear() === prevYear.value && d.getMonth() === prevMonth.value
  })
})

const cambiosEsteMes = computed(() => historialRoomMes.value.length)
const cambiosMesAnterior = computed(() => historialRoomMesAnterior.value.length)
const cambiosDiff = computed(() => cambiosEsteMes.value - cambiosMesAnterior.value)

const incidenciasEsteMes = computed(() => historialRoomMes.value.filter(h => h.nuevo === 'no-funciona').length)
const incidenciasMesAnterior = computed(() => historialRoomMesAnterior.value.filter(h => h.nuevo === 'no-funciona').length)
const incidenciasDiff = computed(() => incidenciasEsteMes.value - incidenciasMesAnterior.value)

const topObservaciones = computed(() => {
  const obs = historialRoomMes.value
    .filter(h => h.observacion && h.observacion.trim())
    .map(h => h.observacion.trim().charAt(0).toUpperCase() + h.observacion.trim().slice(1))
  const counts = {}
  obs.forEach(o => { counts[o] = (counts[o] || 0) + 1 })
  return Object.entries(counts)
    .sort((a, b) => b[1] - a[1])
    .slice(0, 5)
    .map(([text, count]) => ({ text, count }))
})

const ultimaActualizacion = computed(() => {
  const now = new Date()
  return now.toLocaleDateString('es-CO', { day: '2-digit', month: '2-digit', year: 'numeric' }) + ' ' +
    now.toLocaleTimeString('es-CO', { hour: '2-digit', minute: '2-digit' })
})

const now = new Date()
const year = now.getFullYear()
const month = now.getMonth()
const daysInMonth = new Date(year, month + 1, 0).getDate()
const mesNombre = new Date(year, month).toLocaleDateString('es-CO', { month: 'long', year: 'numeric' })

const vista = ref('general')
const pisoSeleccionado = ref('')
const pisoResumen = ref('all')
const vistaTimeline = ref('month')

const distribucionGeneral = computed(() => {
  const counts = { funciona: 0, 'no-funciona': 0, aislado: 0, 'no-hay': 0 }
  store.state.rooms.forEach(r => {
    if (counts[r.estado] !== undefined) counts[r.estado]++
  })
  return { ...counts, total: store.state.rooms.length }
})

const pieGradient = computed(() => {
  const d = distribucionGeneral.value
  const total = d.total || 1
  const items = [
    { key: 'funciona', color: '#059669' },
    { key: 'no-funciona', color: '#e11d48' },
    { key: 'aislado', color: '#ea580c' },
    { key: 'no-hay', color: '#475569' }
  ]
  const segments = []
  let start = 0
  items.forEach(item => {
    const pct = (d[item.key] / total) * 100
    if (pct > 0) {
      segments.push(`${item.color} ${start}% ${start + pct}%`)
      start += pct
    }
  })
  return segments.length ? `conic-gradient(${segments.join(', ')})` : 'none'
})

const ordenSecciones = ['Piso 9', 'Piso 8', 'Piso 7', 'Piso 6', 'Piso 5', 'Piso 2', 'Piso 1']

const roomsPorPiso = computed(() => {
  const pisos = {}
  store.state.rooms.forEach(room => {
    let pisoKey = null
    for (const p of ordenSecciones) {
      if (room.section.startsWith(p)) { pisoKey = p; break }
    }
    if (!pisoKey) pisoKey = 'Otros'
    if (!pisos[pisoKey]) pisos[pisoKey] = []
    pisos[pisoKey].push(room)
  })
  return pisos
})

const pisoBarsData = computed(() => {
  const result = []
  const pisos = pisoResumen.value === 'all' ? ordenSecciones : [pisoResumen.value]
  pisos.forEach(piso => {
    const rooms = roomsPorPiso.value[piso] || []
    const counts = { funciona: 0, 'no-funciona': 0, aislado: 0, 'no-hay': 0 }
    rooms.forEach(r => {
      if (counts[r.estado] !== undefined) counts[r.estado]++
    })
    const total = rooms.length || 1
    result.push({
      piso,
      funciona: counts.funciona,
      'no-funciona': counts['no-funciona'],
      aislado: counts.aislado,
      'no-hay': counts['no-hay'],
      funcionaPct: (counts.funciona / total) * 100,
      noFuncionaPct: (counts['no-funciona'] / total) * 100,
      aisladoPct: (counts.aislado / total) * 100,
      noHayPct: (counts['no-hay'] / total) * 100,
      total
    })
  })
  return result
})

const pisoRooms = computed(() => {
  if (!pisoSeleccionado.value) return []
  return roomsPorPiso.value[pisoSeleccionado.value] || []
})

const pisoRoomCounts = computed(() => {
  const counts = { funciona: 0, 'no-funciona': 0, aislado: 0, 'no-hay': 0 }
  pisoRooms.value.forEach(r => {
    if (counts[r.estado] !== undefined) counts[r.estado]++
  })
  return counts
})

const pisoDistr = (estado) => pisoRoomCounts.value[estado] || 0
const pisoDistrPct = (estado) => {
  const total = pisoRooms.value.length || 1
  return ((pisoRoomCounts.value[estado] || 0) / total) * 100
}

const timelineDataFull = computed(() => {
  const roomName = habitacionSeleccionada.value
  if (!roomName) return []

  const historial = store.state.historial.filter(h => h.room_name === roomName)
  const stateMap = { 'funciona': 'F', 'no-funciona': 'M', 'aislado': 'A', 'no-hay': 'NH' }
  const data = []

  for (let d = 1; d <= daysInMonth; d++) {
    const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`
    const cambiosDia = historial.filter(h => h.date === dateStr).sort((a, b) => a.id - b.id)
    const ultimoCambio = cambiosDia.length ? cambiosDia[cambiosDia.length - 1] : null
    const ultimoEstado = ultimoCambio?.nuevo || null

    data.push({
      day: d,
      state: ultimoEstado ? stateMap[ultimoEstado] : null,
      label: ultimoEstado ? stateMap[ultimoEstado] : '—',
      rawState: ultimoEstado || null,
      detalle: ultimoCambio ? {
        hora: ultimoCambio.created_at || ultimoCambio.date,
        observacion: ultimoCambio.observacion || null,
        anterior: ultimoCambio.anterior || null
      } : null,
      key: dateStr
    })
  }

  return data
})

const timelineData = computed(() => {
  const roomName = habitacionSeleccionada.value
  if (!roomName) return []

  if (vistaTimeline.value === 'month') return timelineDataFull.value

  const dayNames = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb']
  const now = new Date()
  const dayOfWeek = now.getDay()
  const monday = new Date(now)
  monday.setDate(now.getDate() - ((dayOfWeek + 6) % 7))

  const historial = store.state.historial.filter(h => h.room_name === roomName)
  const stateMap = { 'funciona': 'F', 'no-funciona': 'M', 'aislado': 'A', 'no-hay': 'NH' }
  const days = []

  for (let i = 0; i < 7; i++) {
    const d = new Date(monday)
    d.setDate(monday.getDate() + i)
    const dateStr = `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`
    const cambiosDia = historial.filter(h => h.date === dateStr).sort((a, b) => a.id - b.id)
    const ultimoCambio = cambiosDia.length ? cambiosDia[cambiosDia.length - 1] : null
    const ultimoEstado = ultimoCambio?.nuevo || null

    days.push({
      day: d.getDate(),
      dayName: dayNames[d.getDay()],
      state: ultimoEstado ? stateMap[ultimoEstado] : null,
      label: ultimoEstado ? stateMap[ultimoEstado] : '—',
      rawState: ultimoEstado || null,
      detalle: ultimoCambio ? {
        hora: ultimoCambio.created_at || ultimoCambio.date,
        observacion: ultimoCambio.observacion || null,
        anterior: ultimoCambio.anterior || null
      } : null,
      key: dateStr,
      isOtherMonth: d.getMonth() !== month || d.getFullYear() !== year
    })
  }

  return days
})

const estadosCount = computed(() => {
  const counts = { F: 0, M: 0, A: 0, NH: 0 }
  timelineDataFull.value.forEach(d => {
    if (d.state && counts[d.state] !== undefined) counts[d.state]++
  })
  return counts
})

const toggleVistaTimeline = () => {
  vistaTimeline.value = vistaTimeline.value === 'month' ? 'week' : 'month'
}

const modalSecciones = computed(() => {
  const rooms = store.state.rooms
  const seccionesMap = {}

  const q = busqueda.value.toLowerCase().trim()
  rooms.forEach(room => {
    if (q && !room.name.toLowerCase().includes(q) && !room.room_id?.toLowerCase().includes(q)) return
    const pisoMatch = modalFiltroPiso.value === 'all' || room.section.startsWith(modalFiltroPiso.value + ' ')
    if (!pisoMatch) return
    if (modalFiltroTipo.value === 'llamados' && room.section.includes('Baños')) return
    if (modalFiltroTipo.value === 'baños' && !room.section.includes('Baños')) return

    if (!seccionesMap[room.section]) {
      seccionesMap[room.section] = { nombre: room.section, rooms: [] }
    }
    seccionesMap[room.section].rooms.push(room)
  })

  return Object.values(seccionesMap).sort((a, b) => {
    const ai = ordenSecciones.findIndex(o => a.nombre.startsWith(o))
    const bi = ordenSecciones.findIndex(o => b.nombre.startsWith(o))
    return (ai === -1 ? 999 : ai) - (bi === -1 ? 999 : bi)
  })
})

const totalHabitaciones = computed(() => {
  return modalSecciones.value.reduce((sum, s) => sum + s.rooms.length, 0)
})

const estadoLabel = (estado) => {
  const map = {
    'funciona': 'Funciona',
    'no-funciona': 'No Funciona',
    'aislado': 'Aislado',
    'no-hay': 'Sin Llamado'
  }
  return map[estado] || estado
}
</script>

<style scoped>
.analisis {
  padding: 1.5rem;
}

.analisis-toolbar {
  margin-bottom: 1.25rem;
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.view-toggle-group {
  display: flex;
  background: var(--surface);
  border-radius: var(--radius-md);
  padding: 3px;
  border: 1px solid var(--border);
  flex-shrink: 0;
}

.view-toggle-btn {
  display: inline-flex;
  align-items: center;
  gap: 0.35rem;
  padding: 0.45rem 0.75rem;
  font-size: 0.78rem;
  font-weight: 600;
  border: none;
  background: transparent;
  color: var(--text2);
  cursor: pointer;
  border-radius: 5px;
  transition: all 0.2s ease;
  white-space: nowrap;
}

.view-toggle-btn:hover {
  color: var(--text);
}

.view-toggle-btn.active {
  background: var(--surface);
  color: var(--azul);
  box-shadow: 0 1px 3px rgba(0,0,0,0.08);
}

.search-tag {
  display: inline-flex;
  align-items: center;
  gap: 0.35rem;
  background: linear-gradient(135deg, #ede9fe, #ddd6fe);
  color: #6d28d9;
  font-size: 0.78rem;
  font-weight: 600;
  padding: 0.35rem 0.75rem;
  border-radius: var(--radius-sm);
  white-space: nowrap;
}

.btn-seleccionar {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  padding: 0.55rem 1rem;
  font-size: 0.82rem;
  font-weight: 600;
  white-space: nowrap;
  flex-shrink: 0;
}

.analisis-tag-bar {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  margin-bottom: 1rem;
}

.modal-overlay {
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.45);
  backdrop-filter: blur(4px);
  z-index: 1000;
  align-items: center;
  justify-content: center;
}

.modal-overlay.open {
  display: flex;
}

.modal.analisis-modal {
  background: var(--surface);
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow-xl);
  width: 680px;
  max-width: 94vw;
  max-height: 80vh;
  display: flex;
  flex-direction: column;
  animation: modalIn 0.2s ease;
}

@keyframes modalIn {
  from { opacity: 0; transform: scale(0.95) translateY(10px); }
  to { opacity: 1; transform: scale(1) translateY(0); }
}

.modal-header {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 1rem 1.25rem;
  border-bottom: 1px solid var(--border);
  font-size: 0.9rem;
  font-weight: 700;
  color: var(--text);
}

.modal-header svg {
  color: var(--azul);
  flex-shrink: 0;
}

.modal-hab-count {
  margin-left: auto;
  font-size: 0.72rem;
  font-weight: 500;
  color: var(--text2);
  background: var(--surface3);
  border: 1px solid var(--border);
  border-radius: 5px;
  padding: 0.1rem 0.5rem;
}

.modal-close {
  background: none;
  border: none;
  color: var(--text2);
  cursor: pointer;
  padding: 4px;
  border-radius: 4px;
  display: flex;
  margin-left: 0.25rem;
  transition: all 0.2s ease;
}

.modal-close:hover {
  color: var(--text);
  background: var(--surface3);
}

.modal-filtros {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.5rem 1.25rem;
  border-bottom: 1px solid var(--border);
  background: var(--surface3);
}

.modal-filtros .toggle-group {
  display: flex;
  background: var(--surface);
  border-radius: var(--radius-sm);
  padding: 2px;
  border: 1px solid var(--border);
  flex-shrink: 0;
}

.modal-filtros .toggle-btn {
  display: inline-flex;
  align-items: center;
  gap: 0.35rem;
  padding: 0.35rem 0.6rem;
  font-size: 0.75rem;
  font-weight: 600;
  border: none;
  background: transparent;
  color: var(--text2);
  cursor: pointer;
  border-radius: 4px;
  transition: all 0.2s ease;
  white-space: nowrap;
}

.modal-filtros .toggle-btn:hover {
  color: var(--text);
}

.modal-filtros .toggle-btn.active {
  background: var(--surface);
  color: var(--azul);
  box-shadow: 0 1px 3px rgba(0,0,0,0.08);
}

.modal-filtros .filter-select-wrap {
  display: flex;
  align-items: center;
  gap: 0.35rem;
  flex-shrink: 0;
}

.modal-filtros .filter-label {
  font-size: 0.7rem;
  font-weight: 600;
  color: var(--text2);
  text-transform: uppercase;
  letter-spacing: 0.3px;
}

.modal-filtros .filter-select {
  padding: 0.35rem 1.5rem 0.35rem 0.5rem;
  border: 1px solid var(--border);
  border-radius: var(--radius-sm);
  background: var(--surface);
  font-size: 0.78rem;
  font-family: inherit;
  color: var(--text);
  cursor: pointer;
  outline: none;
  appearance: none;
  background-image: url("data:image/svg+xml,%3Csvg width='10' height='6' viewBox='0 0 10 6' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1l4 4 4-4' stroke='%23999' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 6px center;
}

.modal-filtros .filter-select:focus {
  border-color: var(--azul);
  box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.1);
}

.modal-busqueda {
  position: relative;
  margin-bottom: 0.75rem;
}

.modal-busqueda-icon {
  position: absolute;
  left: 10px;
  top: 50%;
  transform: translateY(-50%);
  color: var(--text2);
  pointer-events: none;
}

.modal-busqueda-input {
  width: 100%;
  padding: 0.55rem 0.75rem 0.55rem 2rem;
  border: 1px solid var(--border);
  border-radius: var(--radius-md);
  background: var(--surface3);
  font-size: 0.82rem;
  font-family: inherit;
  color: var(--text);
  outline: none;
  box-sizing: border-box;
  transition: border-color 0.2s ease, background 0.2s ease;
}

.modal-busqueda-input:focus {
  border-color: var(--azul);
  background: var(--surface);
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.08);
}

.tag-label {
  font-size: 0.78rem;
  font-weight: 500;
  color: var(--text2);
  white-space: nowrap;
}

.btn-trash {
  background: none;
  border: none;
  color: #dc2626;
  cursor: pointer;
  padding: 6px;
  border-radius: var(--radius-sm);
  display: flex;
  transition: all 0.2s ease;
  opacity: 0.6;
}

.btn-trash:hover {
  opacity: 1;
  background: rgba(220, 38, 38, 0.08);
}

.room-focus-header {
  position: relative;
  display: flex;
  align-items: flex-start;
  gap: 1rem;
  padding: 1rem 1.25rem;
  background: #fff;
  border: 1px solid #e2e8f0;
  border-radius: 14px;
  margin-bottom: 1.25rem;
  box-shadow: 0 2px 12px rgba(0,0,0,0.04);
}
.room-focus-top {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  flex: 1;
}
.room-focus-icon {
  width: 44px;
  height: 44px;
  border-radius: 12px;
  background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
  color: #4338ca;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.room-focus-info {
  flex: 1;
  min-width: 0;
}
.room-focus-name {
  font-size: 1rem;
  font-weight: 800;
  color: #0f172a;
  letter-spacing: -0.01em;
}
.room-focus-meta {
  font-size: 0.72rem;
  color: #94a3b8;
  margin-top: 2px;
}
.room-focus-chip {
  font-size: 0.62rem;
  font-weight: 700;
  padding: 4px 12px;
  border-radius: 100px;
  text-transform: uppercase;
  letter-spacing: 0.03em;
  flex-shrink: 0;
}
.room-focus-chip.funciona { background: #dcfce7; color: #16a34a; }
.room-focus-chip.no-funciona { background: #fef2f2; color: #dc2626; }
.room-focus-chip.aislado { background: #fffbeb; color: #d97706; }
.room-focus-chip.no-hay { background: #f1f5f9; color: #64748b; }
.room-focus-chip.no-revisado { background: #f1f5f9; color: #94a3b8; }

.room-focus-close {
  position: absolute;
  top: 0.5rem;
  right: 0.5rem;
}

.modal-body {
  padding: 1rem 1.25rem;
  overflow-y: auto;
  flex: 1;
}

.modal-seccion {
  margin-bottom: 1rem;
}

.modal-seccion:last-child {
  margin-bottom: 0;
}

.modal-seccion-titulo {
  font-size: 0.72rem;
  font-weight: 700;
  color: var(--text2);
  text-transform: uppercase;
  letter-spacing: 0.3px;
  margin-bottom: 0.4rem;
  display: flex;
  align-items: center;
  gap: 0.35rem;
}

.modal-seccion-count {
  font-size: 0.65rem;
  font-weight: 600;
  color: var(--text2);
  background: var(--surface3);
  border-radius: 4px;
  padding: 0.05rem 0.4rem;
}

.modal-rooms {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
  gap: 0.35rem;
}

.modal-room {
  display: flex;
  align-items: center;
  gap: 0.4rem;
  padding: 0.4rem 0.6rem;
  border-radius: var(--radius-sm);
  border: 1px solid var(--border);
  background: var(--surface);
  font-size: 0.78rem;
  cursor: pointer;
  transition: all 0.15s ease;
}

.modal-room:hover {
  background: var(--surface3);
  border-color: var(--border2);
  transform: translateY(-1px);
  box-shadow: var(--shadow-sm);
}

.modal-room.funciona { border-left: 3px solid #22c55e; }
.modal-room.no-funciona { border-left: 3px solid #ef4444; }
.modal-room.aislado { border-left: 3px solid #f59e0b; }
.modal-room.no-hay { border-left: 3px solid #9ca3af; }

.modal-room-name {
  color: var(--text);
  font-weight: 500;
}

.modal-empty {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.5rem;
  padding: 2rem;
  color: var(--text2);
  font-size: 0.85rem;
}

.analisis-header {
  display: flex;
  justify-content: flex-end;
  margin-bottom: 1rem;
}

.btn-outline {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  padding: 0.5rem 0.9rem;
  border: 1px solid var(--border);
  border-radius: var(--radius-md);
  background: var(--surface);
  color: var(--text2);
  font-size: 0.78rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
}

.btn-outline:hover {
  border-color: var(--border2);
  color: var(--text);
  background: var(--surface3);
}

.analytics-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
  gap: 1rem;
}

.analytics-card {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: var(--radius-lg);
  padding: 1.5rem;
  box-shadow: var(--shadow-sm);
  display: flex;
  flex-direction: column;
  gap: 1.25rem;
}

.analytics-card-title {
  display: flex;
  align-items: center;
  gap: 0.6rem;
  font-size: 0.88rem;
  font-weight: 700;
  color: var(--text);
}

.analytics-icon {
  width: 34px;
  height: 34px;
  border-radius: var(--radius-md);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.analytics-metrics {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 0.75rem;
}

.analytics-metric {
  display: flex;
  flex-direction: column;
  gap: 0.15rem;
}

.analytics-label {
  font-size: 0.68rem;
  font-weight: 500;
  color: var(--text2);
  text-transform: uppercase;
  letter-spacing: 0.2px;
}

.analytics-value {
  font-size: 1.5rem;
  font-weight: 800;
  color: var(--text);
  line-height: 1.2;
}

.analytics-row {
  display: flex;
  align-items: center;
  gap: 0.3rem;
}

.analytics-change {
  font-size: 0.7rem;
  font-weight: 600;
}

.analytics-change.up {
  color: #16a34a;
}

.analytics-change.down {
  color: #dc2626;
}

.analytics-arrow {
  font-size: 0.65rem;
}

.sparkline {
  margin-top: 0.25rem;
  flex-shrink: 0;
}

/* ===== Summary Dashboard ===== */
.summary-dashboard {
  margin-bottom: 1.5rem;
}

.dashboard-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
}

@media (max-width: 900px) {
  .dashboard-grid {
    grid-template-columns: 1fr;
  }
}

.dashboard-card {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: var(--radius-lg);
  padding: 1.25rem;
  box-shadow: var(--shadow-sm);
}

.dashboard-card-title {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.8rem;
  font-weight: 700;
  color: var(--text);
  margin-bottom: 1rem;
}

.dashboard-card-title svg {
  color: var(--azul);
  flex-shrink: 0;
}

/* Pie Chart */
.pie-container {
  display: flex;
  align-items: center;
  gap: 1.25rem;
}

.pie-chart {
  width: 110px;
  height: 110px;
  border-radius: 50%;
  flex-shrink: 0;
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 2px 12px rgba(0,0,0,0.08), inset 0 1px 3px rgba(255,255,255,0.3);
}

.pie-hole {
  width: 50px;
  height: 50px;
  background: var(--surface);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.95rem;
  font-weight: 800;
  color: var(--text);
}

.pie-legend {
  display: flex;
  flex-direction: column;
  gap: 5px;
  flex: 1;
}

.pie-legend-item {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 0.72rem;
  color: var(--text);
  padding: 5px 8px;
  border-radius: 8px;
  transition: background 0.15s;
}
.pie-legend-item:hover {
  background: #f8fafc;
}

.pie-dot {
  width: 12px;
  height: 12px;
  border-radius: 3px;
  flex-shrink: 0;
  box-shadow: 0 1px 3px rgba(0,0,0,0.15);
}

.pie-label {
  flex: 1;
  font-weight: 600;
}

.pie-count {
  font-weight: 700;
  color: var(--text);
  background: #f1f5f9;
  padding: 2px 8px;
  border-radius: 6px;
  font-size: 0.7rem;
  min-width: 24px;
  text-align: center;
  border: 1px solid #e2e8f0;
}

/* Piso Bars */
.piso-resumen-select {
  margin-left: auto;
  padding: 0.25rem 1.2rem 0.25rem 0.4rem;
  border: 1px solid var(--border);
  border-radius: var(--radius-sm);
  background: var(--surface);
  font-size: 0.7rem;
  font-family: inherit;
  color: var(--text);
  cursor: pointer;
  outline: none;
  appearance: none;
  background-image: url("data:image/svg+xml,%3Csvg width='10' height='6' viewBox='0 0 10 6' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1l4 4 4-4' stroke='%23999' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 4px center;
  font-weight: 500;
}

.piso-bars {
  display: flex;
  flex-direction: column;
  gap: 0.6rem;
}

.piso-bar-row {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 3px 6px;
  border-radius: 6px;
  transition: background 0.12s;
}
.piso-bar-row:hover {
  background: #f8fafc;
}

.piso-bar-label {
  width: 52px;
  font-size: 0.7rem;
  font-weight: 700;
  color: #334155;
  flex-shrink: 0;
  text-align: right;
}

.piso-bar-group {
  flex: 1;
  display: flex;
  gap: 4px;
  flex-wrap: wrap;
}

.piso-bar-item {
  display: flex;
  align-items: center;
  gap: 3px;
  min-width: 0;
}

.piso-bar-track {
  width: 50px;
  height: 10px;
  background: #f1f5f9;
  border-radius: 5px;
  overflow: hidden;
  border: 1px solid #e2e8f0;
}

.piso-bar-fill {
  height: 100%;
  border-radius: 5px;
  transition: width 0.4s ease;
  min-width: 2px;
  box-shadow: inset 0 1px 2px rgba(255,255,255,0.3), 0 1px 3px rgba(0,0,0,0.15);
}

.piso-bar-val {
  font-size: 0.68rem;
  font-weight: 700;
  color: #0f172a;
  min-width: 16px;
  text-align: center;
}

/* ===== Por Piso ===== */
.por-piso-section {
  margin-bottom: 1.5rem;
}

.por-piso-header {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  margin-bottom: 1rem;
}

.por-piso-header svg {
  color: var(--azul);
  flex-shrink: 0;
}

.por-piso-select {
  padding: 0.45rem 1.5rem 0.45rem 0.6rem;
  border: 1px solid var(--border);
  border-radius: var(--radius-md);
  background: var(--surface);
  font-size: 0.82rem;
  font-family: inherit;
  color: var(--text);
  cursor: pointer;
  outline: none;
  appearance: none;
  background-image: url("data:image/svg+xml,%3Csvg width='10' height='6' viewBox='0 0 10 6' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1l4 4 4-4' stroke='%23999' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 8px center;
  font-weight: 600;
  min-width: 180px;
}

.por-piso-select:focus {
  border-color: var(--azul);
  box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.1);
}

.por-piso-content {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
}

@media (max-width: 800px) {
  .por-piso-content {
    grid-template-columns: 1fr;
  }
}

.por-piso-chart {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: var(--radius-lg);
  padding: 1.25rem;
  box-shadow: var(--shadow-sm);
}

.por-piso-chart-title {
  font-size: 0.75rem;
  font-weight: 700;
  color: var(--text);
  margin-bottom: 1rem;
}

.por-piso-bars {
  display: flex;
  flex-direction: column;
  gap: 7px;
}

.por-piso-bar-row {
  display: flex;
  align-items: center;
  gap: 10px;
}

.por-piso-bar-label {
  width: 80px;
  font-size: 0.72rem;
  font-weight: 500;
  color: var(--text);
  flex-shrink: 0;
  text-align: right;
}

.por-piso-bar-track {
  flex: 1;
  height: 10px;
  background: var(--surface3);
  border-radius: 5px;
  overflow: hidden;
}

.por-piso-bar-fill {
  height: 100%;
  border-radius: 5px;
  transition: width 0.5s ease;
  min-width: 2px;
}

.por-piso-bar-count {
  width: 36px;
  font-size: 0.72rem;
  font-weight: 600;
  color: var(--text);
  flex-shrink: 0;
  text-align: right;
}

.por-piso-rooms {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: var(--radius-lg);
  padding: 1.25rem;
  box-shadow: var(--shadow-sm);
}

.por-piso-rooms-title {
  font-size: 0.75rem;
  font-weight: 700;
  color: var(--text);
  margin-bottom: 0.75rem;
}

.por-piso-rooms-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
  gap: 0.35rem;
}

.por-piso-room {
  display: flex;
  align-items: center;
  gap: 0.4rem;
  padding: 0.35rem 0.5rem;
  border-radius: var(--radius-sm);
  border: 1px solid var(--border);
  background: var(--surface);
  font-size: 0.75rem;
  cursor: pointer;
  transition: all 0.15s ease;
}

.por-piso-room:hover {
  background: var(--surface3);
  border-color: var(--border2);
  transform: translateY(-1px);
  box-shadow: var(--shadow-sm);
}

.por-piso-room.funciona { border-left: 3px solid #22c55e; }
.por-piso-room.no-funciona { border-left: 3px solid #ef4444; }
.por-piso-room.aislado { border-left: 3px solid #f59e0b; }
.por-piso-room.no-hay { border-left: 3px solid #9ca3af; }

.por-piso-room-name {
  color: var(--text);
  font-weight: 500;
  font-size: 0.72rem;
}

.por-piso-empty {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.5rem;
  padding: 2.5rem 1rem;
  color: var(--text2);
  font-size: 0.85rem;
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: var(--radius-lg);
}

.chip {
  font-size: 0.6rem;
  font-weight: 600;
  padding: 0.1rem 0.4rem;
  border-radius: 4px;
  white-space: nowrap;
}

.chip.funciona { background: #dcfce7; color: #15803d; }
.chip.no-funciona { background: #fecaca; color: #dc2626; }
.chip.aislado { background: #fef3c7; color: #b45309; }
.chip.no-hay { background: #f1f5f9; color: #64748b; }

/* ===== Timeline ===== */
.timeline-wrap {
  margin-top: 0;
}

.timeline-card {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: var(--radius-lg);
  padding: 1.5rem;
  box-shadow: var(--shadow-sm);
}

.timeline-title {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.82rem;
  font-weight: 700;
  color: var(--text);
  margin-bottom: 1.25rem;
}

.timeline-title svg {
  color: var(--azul);
  flex-shrink: 0;
}

.timeline-mes {
  margin-left: auto;
  font-size: 0.72rem;
  font-weight: 500;
  color: var(--text2);
  background: var(--surface3);
  border: 1px solid var(--border);
  border-radius: 5px;
  padding: 0.15rem 0.55rem;
  text-transform: capitalize;
}

.btn-week-toggle {
  display: inline-flex;
  align-items: center;
  gap: 0.3rem;
  padding: 0.4rem 0.7rem;
  font-size: 0.7rem;
  font-weight: 600;
  color: var(--text2);
  background: var(--surface3);
  border: 1px solid var(--border);
  border-radius: 6px;
  cursor: pointer;
  transition: all 0.2s ease;
  white-space: nowrap;
  flex-shrink: 0;
}

.btn-week-toggle:hover {
  color: var(--text);
  background: var(--surface);
  border-color: var(--border2);
}

.btn-week-toggle svg {
  color: var(--text2);
}

.timeline-grid {
  display: grid;
  grid-template-columns: repeat(31, 1fr);
  gap: 2px;
}

.timeline-grid-week {
  grid-template-columns: repeat(7, 1fr) !important;
}

.timeline-col {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 2px;
}

.timeline-other-month .timeline-state {
  opacity: 0.5;
}

.timeline-state {
  width: 100%;
  text-align: center;
  font-size: 0.65rem;
  font-weight: 700;
  padding: 4px 0;
  border-radius: 4px;
  line-height: 1;
  border: 1px solid transparent;
  box-shadow: 0 1px 2px rgba(0,0,0,0.04);
}

.timeline-state.state-F {
  background: linear-gradient(135deg, #dcfce7, #bbf7d0);
  color: #15803d;
  border-color: #86efac;
  box-shadow: 0 1px 3px rgba(34,197,94,0.15);
}

.timeline-state.state-M {
  background: linear-gradient(135deg, #fecaca, #fca5a5);
  color: #dc2626;
  border-color: #f87171;
  box-shadow: 0 1px 3px rgba(239,68,68,0.15);
}

.timeline-state.state-A {
  background: linear-gradient(135deg, #fef3c7, #fde68a);
  color: #b45309;
  border-color: #fbbf24;
  box-shadow: 0 1px 3px rgba(245,158,11,0.15);
}

.timeline-state.state-NH {
  background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
  color: #64748b;
  border-color: #cbd5e1;
  box-shadow: 0 1px 2px rgba(0,0,0,0.04);
}

.timeline-state:not(.state-F):not(.state-M):not(.state-A):not(.state-NH) {
  background: #fafbfc;
  color: #cbd5e1;
  border: 1px dashed #e2e8f0;
  box-shadow: none;
}

.timeline-state {
  cursor: pointer;
  transition: transform 0.15s, box-shadow 0.15s;
}

.timeline-state:hover {
  transform: scale(1.14);
  box-shadow: 0 0 0 3px rgba(99,102,241,0.3), 0 4px 12px rgba(0,0,0,0.12);
  z-index: 2;
  position: relative;
}

.timeline-state {
  position: relative;
}

.timeline-tooltip {
  display: none;
  position: absolute;
  bottom: calc(100% + 6px);
  left: 50%;
  transform: translateX(-50%);
  background: #fff;
  color: #1e293b;
  font-size: 0.65rem;
  font-weight: 500;
  padding: 0.5rem 0.65rem 0.5rem 0.8rem;
  border-radius: 8px;
  white-space: nowrap;
  z-index: 50;
  box-shadow: 0 0 0 3px rgba(99,102,241,0.15), 0 8px 24px rgba(0,0,0,0.12);
  border: 1px solid #e2e8f0;
  border-left: 3px solid #6366f1;
  line-height: 1.5;
  pointer-events: none;
  flex-direction: column;
  align-items: flex-start;
  animation: tipPop 0.18s ease-out;
}

.timeline-tooltip::after {
  content: '';
  position: absolute;
  top: 100%;
  left: 50%;
  transform: translateX(-50%);
  border: 5px solid transparent;
  border-top-color: #e2e8f0;
}

.timeline-tooltip::before {
  content: '';
  position: absolute;
  top: calc(100% - 1px);
  left: 50%;
  transform: translateX(-50%);
  border: 4px solid transparent;
  border-top-color: #fff;
  z-index: 1;
}

.timeline-state:hover .timeline-tooltip {
  display: flex;
}

.tt-header {
  font-weight: 700;
  font-size: 0.68rem;
  margin-bottom: 2px;
  white-space: nowrap;
}

.tt-state-F { color: #4ade80; }
.tt-state-M { color: #f87171; }
.tt-state-A { color: #fbbf24; }
.tt-state-NH { color: #94a3b8; }
.tt-row {
  white-space: nowrap;
}
.tt-obs {
  max-width: 220px;
  white-space: normal;
  word-break: break-word;
}

.btn-resumen {
  display: inline-flex;
  align-items: center;
  gap: 0.35rem;
  padding: 0.4rem 0.8rem;
  font-size: 0.72rem;
  font-weight: 600;
  color: #fff;
  background: #1E4EA8;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  transition: background 0.2s, transform 0.15s;
  margin-left: auto;
  white-space: nowrap;
  flex-shrink: 0;
}

.btn-resumen:hover {
  background: #2563eb;
  transform: translateY(-1px);
}

.btn-resumen svg {
  flex-shrink: 0;
}

/* ===== Resumen Card ===== */
.resumen-card {
  width: 480px;
  max-width: 92vw;
  background: var(--surface, #fff);
  border-radius: 16px;
  box-shadow: 0 20px 60px rgba(0,0,0,0.2), 0 4px 12px rgba(0,0,0,0.06);
  animation: resumenIn 0.25s ease;
  position: relative;
  padding: 1.5rem;
}

@keyframes resumenIn {
  from { opacity: 0; transform: scale(0.95) translateY(12px); }
  to { opacity: 1; transform: scale(1) translateY(0); }
}

.resumen-close {
  position: absolute;
  top: 10px;
  right: 10px;
  z-index: 10;
  background: var(--surface3, #f1f5f9);
  border: none;
  border-radius: 50%;
  width: 32px;
  height: 32px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--text2, #64748b);
  cursor: pointer;
  transition: background 0.2s, transform 0.15s;
}

.resumen-close:hover {
  background: var(--border, #e2e8f0);
  transform: scale(1.1);
}

.resumen-top {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 0.25rem;
}

.resumen-room {
  display: flex;
  align-items: center;
  gap: 10px;
}

.resumen-room-icon {
  width: 40px;
  height: 40px;
  border-radius: 10px;
  background: var(--azul, #0D2D6B);
  color: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.resumen-room-sub {
  font-size: 0.68rem;
  color: var(--text2, #64748b);
  font-weight: 500;
  text-transform: uppercase;
  letter-spacing: 0.04em;
}

.resumen-room-num {
  font-size: 1.4rem;
  font-weight: 800;
  color: var(--text, #1e293b);
  line-height: 1.1;
}

.resumen-chip {
  display: flex;
  align-items: center;
  gap: 6px;
  padding: 6px 14px;
  border-radius: 20px;
  font-size: 0.78rem;
  font-weight: 600;
}

.resumen-chip.chip-F { background: #dcfce7; color: #15803d; }
.resumen-chip.chip-M { background: #fecaca; color: #dc2626; }
.resumen-chip.chip-A { background: #fef3c7; color: #b45309; }
.resumen-chip.chip-NH { background: #f1f5f9; color: #64748b; }

.resumen-chip-letter {
  font-weight: 800;
  font-size: 0.9rem;
}

.resumen-mes {
  font-size: 0.78rem;
  font-weight: 600;
  color: var(--text2, #64748b);
  margin-bottom: 1rem;
  padding-bottom: 0.75rem;
  border-bottom: 1px solid var(--border, #e2e8f0);
}

.resumen-chart {
  margin-bottom: 1rem;
}

.resumen-chart-title {
  font-size: 0.65rem;
  font-weight: 600;
  color: var(--text2, #64748b);
  text-transform: uppercase;
  letter-spacing: 0.04em;
  margin-bottom: 8px;
}

.resumen-chart-bars {
  display: flex;
  flex-direction: column;
  gap: 7px;
}

.resumen-chart-row {
  display: flex;
  align-items: center;
  gap: 10px;
}

.resumen-chart-label {
  width: 80px;
  font-size: 0.72rem;
  font-weight: 500;
  color: var(--text, #1e293b);
  flex-shrink: 0;
  text-align: right;
}

.resumen-chart-track {
  flex: 1;
  height: 10px;
  background: var(--surface3, #f1f5f9);
  border-radius: 5px;
  overflow: hidden;
}

.resumen-chart-fill {
  height: 100%;
  border-radius: 5px;
  transition: width 0.5s ease;
  min-width: 2px;
}

.resumen-chart-count {
  width: 70px;
  font-size: 0.72rem;
  font-weight: 600;
  color: var(--text, #1e293b);
  flex-shrink: 0;
}

.resumen-chart-pct {
  font-weight: 400;
  color: var(--text2, #94a3b8);
  font-size: 0.65rem;
}

.resumen-topobs {
  background: var(--surface3, #f8fafc);
  border: 1px solid var(--border, #e2e8f0);
  border-radius: 10px;
  padding: 12px 14px;
  margin-bottom: 0.75rem;
}

.resumen-topobs-text {
  font-size: 0.85rem;
  font-weight: 600;
  color: var(--text, #1e293b);
  line-height: 1.4;
  margin-bottom: 4px;
}

.resumen-topobs-footer {
  display: flex;
  align-items: center;
  gap: 10px;
}

.resumen-topobs-count {
  font-size: 0.68rem;
  font-weight: 600;
  color: var(--azul, #0D2D6B);
  background: rgba(13, 45, 107, 0.08);
  padding: 1px 8px;
  border-radius: 4px;
}

.resumen-topobs-mas {
  font-size: 0.68rem;
  font-weight: 500;
  color: var(--azul, #0D2D6B);
  cursor: pointer;
  text-decoration: underline;
  text-underline-offset: 2px;
}

.resumen-topobs-mas:hover {
  opacity: 0.8;
}

.resumen-obs {
  margin-bottom: 0.5rem;
}

.resumen-obs-title {
  font-size: 0.65rem;
  font-weight: 600;
  color: var(--text2, #64748b);
  text-transform: uppercase;
  letter-spacing: 0.04em;
  margin-bottom: 6px;
}

.resumen-obs-tags {
  display: flex;
  flex-wrap: wrap;
  gap: 6px;
}

.resumen-obs-tag {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  padding: 4px 10px;
  background: var(--surface3, #f1f5f9);
  border: 1px solid var(--border, #e2e8f0);
  border-radius: 6px;
  font-size: 0.7rem;
  font-weight: 500;
  color: var(--text, #475569);
}

.resumen-obs-count {
  background: var(--border, #e2e8f0);
  border-radius: 4px;
  padding: 0 5px;
  font-size: 0.6rem;
  font-weight: 700;
  color: var(--text2, #64748b);
}

.resumen-nota {
  display: flex;
  align-items: flex-start;
  gap: 8px;
  padding: 10px 12px;
  background: #fffbeb;
  border: 1px solid #fde68a;
  border-radius: 8px;
  font-size: 0.78rem;
  color: #92400e;
  line-height: 1.5;
}

.resumen-nota svg {
  flex-shrink: 0;
  margin-top: 2px;
  color: #d97706;
}

.resumen-empty {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 0.5rem 0;
  color: var(--text2, #94a3b8);
  font-style: italic;
  font-size: 0.8rem;
}

.timeline-day {
  font-size: 0.55rem;
  color: var(--text2);
  font-weight: 600;
  text-align: center;
}

.timeline-day-week {
  font-size: 0.5rem;
  color: var(--azul);
  font-weight: 700;
}

.timeline-legend {
  display: flex;
  flex-wrap: wrap;
  gap: 1rem;
  margin-top: 1rem;
  padding-top: 0.75rem;
  border-top: 1px solid var(--border);
  font-size: 0.7rem;
  color: var(--text2);
}

.timeline-legend span {
  display: flex;
  align-items: center;
  gap: 3px;
}

.legend-dot {
  width: 10px;
  height: 10px;
  border-radius: 3px;
  display: inline-block;
}

.timeline-empty {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.5rem;
  padding: 2.5rem 1rem;
  color: var(--text2);
  font-size: 0.85rem;
}

.piso-summary {
  margin: 20px 0 16px;
}
.piso-summary-title {
  font-size: 14px;
  font-weight: 700;
  color: #0f172a;
  margin: 0 0 10px;
}
.piso-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 10px;
}
.piso-card-dash {
  background: #fff;
  border: 1px solid #e2e8f0;
  border-radius: 10px;
  padding: 12px 14px;
  position: relative;
  overflow: hidden;
  box-shadow: 0 2px 6px rgba(0,0,0,0.03);
  transition: all 0.2s;
}
.piso-card-dash:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 18px rgba(0,0,0,0.06);
}
.piso-card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 6px;
}
.piso-card-name {
  font-size: 13px;
  font-weight: 800;
  color: #0f172a;
}
.piso-card-total {
  font-size: 10px;
  font-weight: 600;
  color: #94a3b8;
}
.piso-card-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2px;
}
.piso-card-label {
  font-size: 10px;
  color: #64748b;
  font-weight: 500;
}
.piso-card-num {
  font-size: 11px;
  font-weight: 700;
  color: #0f172a;
}
.piso-card-pending {
  color: #f59e0b;
}
.piso-card-bar {
  height: 3px;
  background: #f1f5f9;
  border-radius: 3px;
  overflow: hidden;
  margin-bottom: 4px;
}
.piso-card-fill {
  height: 100%;
  border-radius: 3px;
  background: linear-gradient(90deg, #22c55e, #16a34a);
  transition: width 1s cubic-bezier(0.34, 1.56, 0.64, 1);
}
.piso-card-health {
  position: absolute;
  top: 8px;
  right: 8px;
  width: 6px;
  height: 6px;
  border-radius: 50%;
}
.health-green { background: #22c55e; }
.health-yellow { background: #eab308; }
.health-red { background: #ef4444; }

.alerts-section {
  margin-bottom: 16px;
}
.alerts-list {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 8px;
}
.alert-card {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 10px 12px;
  background: #fff;
  border: 1px solid #fecaca;
  border-radius: 8px;
  transition: all 0.15s;
}
.alert-card:hover {
  border-color: #fca5a5;
  background: #fef2f2;
}
.alert-icon {
  width: 28px;
  height: 28px;
  border-radius: 6px;
  background: #fef2f2;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  color: #ef4444;
}
.alert-icon svg { width: 14px; height: 14px; }
.alert-body { flex: 1; min-width: 0; }
.alert-body strong { display: block; font-size: 12px; font-weight: 700; color: #0f172a; }
@keyframes tipPop { from { opacity: 0; transform: translateX(-50%) scale(0.92); } to { opacity: 1; transform: translateX(-50%) scale(1); } }
.alert-body span { font-size: 10px; color: #64748b; }

</style>
