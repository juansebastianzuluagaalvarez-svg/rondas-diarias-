<template>
  <div class="nuevo-wrap">
    <Transition name="fade" mode="out-in">
      <div v-if="!modo" key="menu">
        <div class="hero-header">
          <div class="hero-badge">Gestión</div>
          <h2 class="hero-title">Llamados</h2>
          <p class="hero-desc">Administrá las habitaciones y baños del sistema</p>
        </div>

        <div class="modo-selector">
          <div class="modo-card" @click="modo = 'crear'; paso = 'resumen'" style="--accent:#3b82f6;--accent-bg:#eff6ff">
            <div class="modo-icon">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
            </div>
            <div class="modo-body">
              <h3>Crear Llamado</h3>
              <p>Agregar una nueva habitación o baño</p>
            </div>
            <svg class="modo-arrow" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
          </div>
          <div class="modo-card" @click="modo = 'editar'; paso = 'resumen'" style="--accent:#8b5cf6;--accent-bg:#f5f3ff">
            <div class="modo-icon">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            </div>
            <div class="modo-body">
              <h3>Editar Llamado</h3>
              <p>Modificar nombre, sección o tipo</p>
            </div>
            <svg class="modo-arrow" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
          </div>
          <div class="modo-card" @click="confirmarEliminar" style="--accent:#ef4444;--accent-bg:#fef2f2">
            <div class="modo-icon">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
            </div>
            <div class="modo-body">
              <h3>Eliminar Llamado</h3>
              <p>Eliminar una habitación o baño del sistema</p>
            </div>
            <svg class="modo-arrow" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
          </div>
        </div>
      </div>

      <div v-else-if="modo === 'crear'" key="crear">
        <div v-if="paso === 'resumen'" class="summary-card" style="--accent:#3b82f6;--accent-bg:#eff6ff">
          <div class="summary-top">
            <button class="btn-back" @click="modo = null; paso = 'resumen'">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><polyline points="12 19 5 12 12 5"/></svg>
              <span>Volver</span>
            </button>
          </div>
          <div class="summary-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
          </div>
          <h3 class="summary-title">Crear Llamado</h3>
          <p class="summary-desc">Agregá una nueva habitación o baño al sistema</p>
          <div class="summary-grid">
            <div>
              <div class="sg-n">{{ statsResumen.total }}</div>
              <div class="sg-l">Habitaciones</div>
              <div class="sg-bar"><div class="sg-fill" style="width:100%;background:linear-gradient(90deg,#93c5fd,#3b82f6)"></div></div>
            </div>
            <div>
              <div class="sg-n">{{ statsResumen.llamados }}</div>
              <div class="sg-l">Llamados</div>
              <div class="sg-bar"><div class="sg-fill" :style="{ width: statsResumen.total ? (statsResumen.llamados/statsResumen.total*100) + '%' : '0%', background: 'linear-gradient(90deg,#a78bfa,#8b5cf6)' }"></div></div>
            </div>
            <div>
              <div class="sg-n">{{ statsResumen.banos }}</div>
              <div class="sg-l">Baños</div>
              <div class="sg-bar"><div class="sg-fill" :style="{ width: statsResumen.total ? (statsResumen.banos/statsResumen.total*100) + '%' : '0%', background: 'linear-gradient(90deg,#6ee7b7,#10b981)' }"></div></div>
            </div>
          </div>
          <div class="summary-actions">
            <button class="btn btn-primary" @click="paso = 'form'">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
              Crear nuevo
            </button>
            <button class="btn btn-ghost" @click="modo = null; paso = 'resumen'">Cancelar</button>
          </div>
        </div>
        <template v-if="paso === 'form'">
        <div class="section-top">
          <button class="btn-back" @click="paso = 'resumen'">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><polyline points="12 19 5 12 12 5"/></svg>
            <span>Volver</span>
          </button>
          <h2 class="section-title">Crear Llamado</h2>
        </div>
        <div class="nuevo-form">
          <div class="form-group">
            <label class="form-label">Sede</label>
            <div class="input-wrap">
              <svg class="input-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
              <select class="form-input" v-model="form.sede">
                <option value="torre">Torre</option>
                <option value="urgencias">Urgencias</option>
              </select>
            </div>
          </div>
          <div class="form-group autocomplete-group">
            <label class="form-label">Piso / Sección</label>
            <div class="input-wrap">
              <svg class="input-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="2" width="16" height="20" rx="2" ry="2"/><line x1="9" y1="6" x2="10" y2="6"/><line x1="14" y1="6" x2="15" y2="6"/><line x1="9" y1="10" x2="10" y2="10"/><line x1="14" y1="10" x2="15" y2="10"/><line x1="9" y1="14" x2="10" y2="14"/><line x1="14" y1="14" x2="15" y2="14"/></svg>
              <input class="form-input" v-model="form.piso" placeholder="Ej: Piso 8, UCI..." @focus="mostrarSugerencias = true" @input="mostrarSugerencias = true" />
            </div>
            <ul v-if="mostrarSugerencias && sugerenciasPiso.length" class="autocomplete-list">
              <li v-for="s in sugerenciasPiso" :key="s" @mousedown.prevent="seleccionarPiso(s)">{{ s }}</li>
            </ul>
          </div>
          <div class="form-group">
            <label class="form-label">Área <span class="label-opt">opcional</span></label>
            <div class="input-wrap">
              <svg class="input-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 20h16"/><path d="M4 20V4"/><path d="M20 20V8"/><path d="M12 20V12"/><path d="M16 20V4"/></svg>
              <input class="form-input" v-model="form.area" placeholder="Hospitalización, Cirugía, UCI..." />
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label class="form-label">ID del llamado</label>
              <div class="input-wrap">
                <svg class="input-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="3" x2="9" y2="21"/></svg>
                <input class="form-input" v-model="form.room_id" placeholder="Ej: 8-ENF" />
              </div>
            </div>
            <div class="form-group">
              <label class="form-label">Nombre</label>
              <div class="input-wrap">
                <svg class="input-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                <input class="form-input" v-model="form.name" placeholder="Estación Enfermería" />
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="form-label">Tipo</label>
            <div class="tipo-selector">
              <label :class="['tipo-opt', { active: form.type === 'llamados' }]">
                <input type="radio" v-model="form.type" value="llamados" />
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                Llamado
              </label>
              <label :class="['tipo-opt', { active: form.type === 'baños' }]">
                <input type="radio" v-model="form.type" value="baños" />
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 8h16"/><rect x="2" y="8" width="20" height="8" rx="2"/><path d="M6 16v3"/><path d="M18 16v3"/></svg>
                Baño
              </label>
            </div>
          </div>
          <div class="form-actions">
            <button class="btn btn-primary" @click="crear" :disabled="!formValido || guardando">
              <svg v-if="!guardando" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
              <template v-if="guardando">Guardando...</template>
              <template v-else>Crear Llamado</template>
            </button>
            <button class="btn btn-ghost" @click="paso = 'resumen'">Cancelar</button>
          </div>
          <Transition name="msg">
            <div v-if="error" class="form-msg error">{{ error }}</div>
          </Transition>
          <Transition name="msg">
            <div v-if="exito" class="form-msg exito">{{ exito }}</div>
          </Transition>
        </div>
        </template>
      </div>

      <div v-else-if="modo === 'editar'" key="editar">
        <div v-if="paso === 'resumen'" class="summary-card" style="--accent:#8b5cf6;--accent-bg:#f5f3ff">
          <div class="summary-top">
            <button class="btn-back" @click="modo = null; paso = 'resumen'">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><polyline points="12 19 5 12 12 5"/></svg>
              <span>Volver</span>
            </button>
          </div>
          <div class="summary-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
          </div>
          <h3 class="summary-title">Editar Llamado</h3>
          <p class="summary-desc">Modificá nombre, sección o tipo de un llamado existente</p>
          <div class="summary-grid">
            <div>
              <div class="sg-n">{{ statsResumen.torre }}</div>
              <div class="sg-l">Torre</div>
              <div class="sg-bar"><div class="sg-fill" :style="{ width: statsResumen.total ? (statsResumen.torre/statsResumen.total*100) + '%' : '0%', background: 'linear-gradient(90deg,#93c5fd,#3b82f6)' }"></div></div>
            </div>
            <div>
              <div class="sg-n">{{ statsResumen.urgencias }}</div>
              <div class="sg-l">Urgencias</div>
              <div class="sg-bar"><div class="sg-fill" :style="{ width: statsResumen.total ? (statsResumen.urgencias/statsResumen.total*100) + '%' : '0%', background: 'linear-gradient(90deg,#fca5a5,#ef4444)' }"></div></div>
            </div>
            <div>
              <div class="sg-n">{{ statsResumen.total }}</div>
              <div class="sg-l">Total</div>
              <div class="sg-bar"><div class="sg-fill" style="width:100%;background:linear-gradient(90deg,#c4b5fd,#8b5cf6)"></div></div>
            </div>
          </div>
          <div class="summary-actions">
            <button class="btn btn-primary" @click="paso = 'form'" style="background:linear-gradient(135deg,#8b5cf6,#7c3aed);box-shadow:0 4px 12px rgba(139,92,246,0.25)">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
              Seleccionar para editar
            </button>
            <button class="btn btn-ghost" @click="modo = null; paso = 'resumen'">Cancelar</button>
          </div>
        </div>
        <template v-if="paso === 'form'">
        <div class="section-top">
          <button class="btn-back" @click="paso = 'resumen'; seleccionado = null">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><polyline points="12 19 5 12 12 5"/></svg>
            <span>Volver</span>
          </button>
          <h2 class="section-title">Editar Llamado</h2>
        </div>
        <div class="nuevo-form">
          <div class="form-group">
            <label class="form-label">Buscar habitación</label>
            <div class="input-wrap">
              <svg class="input-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
              <input class="form-input" v-model="busqueda" placeholder="Escribí nombre o ID..." @input="seleccionado = null" />
            </div>
          </div>
          <Transition name="list">
            <div v-if="resultados.length" class="lista-rooms">
              <div v-for="r in resultados" :key="r.id" :class="['room-item', { active: seleccionado?.id === r.id }]" @click="seleccionar(r)">
                <div class="room-avatar" :class="r.type === 'baños' ? 'bano' : 'llamado'">
                  <svg v-if="r.type === 'baños'" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 8h16"/><rect x="2" y="8" width="20" height="8" rx="2"/><path d="M6 16v3"/><path d="M18 16v3"/></svg>
                  <svg v-else width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                </div>
                <div class="room-item-info">
                  <strong>{{ r.name }}</strong>
                  <span class="room-meta">{{ r.room_id }} · {{ r.section }}</span>
                </div>
                <svg class="room-chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
              </div>
            </div>
          </Transition>
          <p v-if="!resultados.length && busqueda" class="sin-resultados">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            Sin resultados para "{{ busqueda }}"
          </p>

          <Transition name="slide-up">
            <div v-if="seleccionado" key="edit-panel">
              <div class="form-divider"></div>
              <div class="form-group">
                <label class="form-label">Sede</label>
                <div class="input-wrap">
                  <svg class="input-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                  <select class="form-input" v-model="editForm.sede">
                    <option value="torre">Torre</option>
                    <option value="urgencias">Urgencias</option>
                  </select>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group">
                  <label class="form-label">ID</label>
                  <div class="input-wrap">
                    <svg class="input-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="3" x2="9" y2="21"/></svg>
                    <input class="form-input" v-model="editForm.room_id" />
                  </div>
                </div>
                <div class="form-group">
                  <label class="form-label">Nombre</label>
                  <div class="input-wrap">
                    <svg class="input-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <input class="form-input" v-model="editForm.name" />
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="form-label">Sección</label>
                <div class="input-wrap">
                  <svg class="input-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="2" width="16" height="20" rx="2" ry="2"/><line x1="9" y1="6" x2="10" y2="6"/><line x1="14" y1="6" x2="15" y2="6"/><line x1="9" y1="10" x2="10" y2="10"/><line x1="14" y1="10" x2="15" y2="10"/><line x1="9" y1="14" x2="10" y2="14"/><line x1="14" y1="14" x2="15" y2="14"/></svg>
                  <input class="form-input" v-model="editForm.section" />
                </div>
              </div>
              <div class="form-group">
                <label class="form-label">Tipo</label>
                <div class="tipo-selector">
                  <label :class="['tipo-opt', { active: editForm.type === 'llamados' }]">
                    <input type="radio" v-model="editForm.type" value="llamados" />
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    Llamado
                  </label>
                  <label :class="['tipo-opt', { active: editForm.type === 'baños' }]">
                    <input type="radio" v-model="editForm.type" value="baños" />
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 8h16"/><rect x="2" y="8" width="20" height="8" rx="2"/><path d="M6 16v3"/><path d="M18 16v3"/></svg>
                    Baño
                  </label>
                </div>
              </div>
              <div class="form-actions">
                <button class="btn btn-primary" @click="guardarEdicion" :disabled="guardando" style="background:linear-gradient(135deg,#8b5cf6,#7c3aed);box-shadow:0 4px 12px rgba(139,92,246,0.25)">
                  <svg v-if="!guardando" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 14.66V20a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h5.34"/><polygon points="18 2 22 6 12 16 8 16 8 12 18 2"/></svg>
                  <template v-if="guardando">Guardando...</template>
                  <template v-else>Guardar Cambios</template>
                </button>
                <button class="btn btn-ghost" @click="seleccionado = null">Cancelar</button>
              </div>
            </div>
          </Transition>

          <Transition name="msg">
            <div v-if="error" class="form-msg error">{{ error }}</div>
          </Transition>
          <Transition name="msg">
            <div v-if="exito" class="form-msg exito">{{ exito }}</div>
          </Transition>
        </div>
        </template>
      </div>

      <div v-else-if="modo === 'eliminar'" key="eliminar">
        <div v-if="paso === 'resumen'" class="summary-card" style="--accent:#ef4444;--accent-bg:#fef2f2">
          <div class="summary-top">
            <button class="btn-back" @click="modo = null; paso = 'resumen'">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><polyline points="12 19 5 12 12 5"/></svg>
              <span>Volver</span>
            </button>
          </div>
          <div class="summary-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
          </div>
          <h3 class="summary-title">Eliminar Llamado</h3>
          <p class="summary-desc">Eliminá una habitación o baño del sistema</p>
          <div class="summary-grid">
            <div>
              <div class="sg-n">{{ statsResumen.llamados }}</div>
              <div class="sg-l">Llamados</div>
              <div class="sg-bar"><div class="sg-fill" :style="{ width: statsResumen.total ? (statsResumen.llamados/statsResumen.total*100) + '%' : '0%', background: 'linear-gradient(90deg,#a78bfa,#8b5cf6)' }"></div></div>
            </div>
            <div>
              <div class="sg-n">{{ statsResumen.banos }}</div>
              <div class="sg-l">Baños</div>
              <div class="sg-bar"><div class="sg-fill" :style="{ width: statsResumen.total ? (statsResumen.banos/statsResumen.total*100) + '%' : '0%', background: 'linear-gradient(90deg,#6ee7b7,#10b981)' }"></div></div>
            </div>
            <div>
              <div class="sg-n" style="color:#dc2626">{{ statsResumen.total }}</div>
              <div class="sg-l">Total a eliminar</div>
              <div class="sg-bar"><div class="sg-fill" style="width:100%;background:linear-gradient(90deg,#fca5a5,#ef4444)"></div></div>
            </div>
          </div>
          <div class="summary-actions">
            <button class="btn btn-danger" @click="paso = 'form'">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
              Seleccionar para eliminar
            </button>
            <button class="btn btn-ghost" @click="modo = null; paso = 'resumen'">Cancelar</button>
          </div>
        </div>
        <template v-if="paso === 'form'">
        <div class="section-top">
          <button class="btn-back" @click="paso = 'resumen'">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><polyline points="12 19 5 12 12 5"/></svg>
            <span>Volver</span>
          </button>
          <h2 class="section-title">Eliminar Llamado</h2>
        </div>
        <div class="nuevo-form">
          <div class="form-group">
            <label class="form-label">Buscar habitación</label>
            <div class="input-wrap">
              <svg class="input-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
              <input class="form-input" v-model="busqueda" placeholder="Escribí nombre o ID..." @input="seleccionado = null" />
            </div>
          </div>
          <Transition name="list">
            <div v-if="resultados.length" class="lista-rooms">
              <div v-for="r in resultados" :key="r.id" class="room-item room-item-del" @click="confirmarEliminarRoom(r)">
                <div class="room-avatar del">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                </div>
                <div class="room-item-info">
                  <strong>{{ r.name }}</strong>
                  <span class="room-meta">{{ r.room_id }} · {{ r.section }}</span>
                </div>
                <svg class="room-chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
              </div>
            </div>
          </Transition>
          <p v-if="!resultados.length && busqueda" class="sin-resultados">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            Sin resultados para "{{ busqueda }}"
          </p>
        </div>

        <Transition name="modal">
          <div v-if="eliminarConfirm" class="modal-overlay" @click.self="eliminarConfirm = null">
            <div class="modal-confirm">
              <div class="modal-icon-wrap">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
              </div>
              <h3>¿Eliminar "{{ eliminarConfirm.name }}"?</h3>
              <p>Se eliminarán también sus registros de historial. Esta acción no se puede deshacer.</p>
              <div class="form-actions">
                <button class="btn btn-danger" @click="ejecutarEliminar" :disabled="guardando">
                  <svg v-if="!guardando" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                  <template v-if="guardando">Eliminando...</template>
                  <template v-else>Sí, eliminar</template>
                </button>
                <button class="btn btn-ghost" @click="eliminarConfirm = null">Cancelar</button>
              </div>
              <Transition name="msg">
                <div v-if="error" class="form-msg error" style="margin-top:0.75rem">{{ error }}</div>
              </Transition>
            </div>
          </div>
        </Transition>
        </template>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { reactive, computed, ref, onMounted, onUnmounted } from 'vue'
import { useStore } from '../store'
import { api } from '../api'

const store = useStore()
const modo = ref(null)
const paso = ref('resumen')
const guardando = ref(false)
const error = ref('')
const exito = ref('')
const busqueda = ref('')
const seleccionado = ref(null)
const eliminarConfirm = ref(null)
const rooms = ref([])
const mostrarSugerencias = ref(false)

const statsResumen = computed(() => {
  const total = rooms.value.length
  const llamados = rooms.value.filter(r => r.type === 'llamados').length
  const banos = rooms.value.filter(r => r.type === 'baños').length
  const torre = rooms.value.filter(r => r.sede === 'torre').length
  const urgencias = rooms.value.filter(r => r.sede === 'urgencias').length
  return { total, llamados, banos, torre, urgencias }
})

const sugerenciasPiso = computed(() => {
  if (!form.piso) return []
  const q = form.piso.toLowerCase()
  const sections = rooms.value.map(r => r.section).filter(Boolean)
  const unicos = [...new Set(sections)]
  return unicos.filter(s => s.toLowerCase().includes(q)).slice(0, 12)
})

const cerrarSugerencias = (e) => {
  if (!e.target.closest('.autocomplete-group')) {
    mostrarSugerencias.value = false
  }
}

onMounted(() => document.addEventListener('click', cerrarSugerencias))
onUnmounted(() => document.removeEventListener('click', cerrarSugerencias))

const form = reactive({
  sede: store.state.sede,
  piso: '',
  area: '',
  room_id: '',
  name: '',
  type: 'llamados',
})

const editForm = reactive({
  sede: 'torre',
  room_id: '',
  name: '',
  section: '',
  type: 'llamados',
})

const formValido = computed(() => form.piso && form.room_id && form.name)

const resultados = computed(() => {
  if (!busqueda.value) return []
  const q = busqueda.value.toLowerCase()
  return rooms.value.filter(r =>
    r.name.toLowerCase().includes(q) ||
    r.room_id.toLowerCase().includes(q)
  ).slice(0, 20)
})

const cargarRooms = async () => {
  try {
    rooms.value = await api.getRooms()
  } catch {}
}

const seleccionarPiso = (s) => {
  form.piso = s
  mostrarSugerencias.value = false
}

const seleccionar = (r) => {
  seleccionado.value = r
  editForm.sede = r.sede || 'torre'
  editForm.room_id = r.room_id
  editForm.name = r.name
  editForm.section = r.section
  editForm.type = r.type
}

const crear = async () => {
  error.value = ''
  exito.value = ''
  guardando.value = true
  try {
    const section = form.area
      ? `${form.piso} - ${form.area} - ${form.type === 'llamados' ? 'Llamados' : 'Baños'}`
      : `${form.piso} - ${form.type === 'llamados' ? 'Llamados' : 'Baños'}`
    await api.createRoom({
      room_id: form.room_id,
      name: form.name,
      section,
      type: form.type,
      sede: form.sede,
    })
    exito.value = `"${form.name}" creado correctamente`
    form.room_id = ''
    form.name = ''
    form.area = ''
    form.type = 'llamados'
    await cargarRooms()
    store.showToast('Llamado creado exitosamente', 'success')
  } catch (e) {
    error.value = e.message || 'Error al crear el llamado'
  } finally {
    guardando.value = false
  }
}

const guardarEdicion = async () => {
  error.value = ''
  exito.value = ''
  guardando.value = true
  try {
    await api.updateRoom(seleccionado.value.id, {
      room_id: editForm.room_id,
      name: editForm.name,
      section: editForm.section,
      type: editForm.type,
      sede: editForm.sede,
    })
    exito.value = 'Cambios guardados correctamente'
    await cargarRooms()
    seleccionado.value = null
    busqueda.value = ''
    store.showToast('Llamado actualizado', 'success')
  } catch (e) {
    error.value = e.message || 'Error al actualizar'
  } finally {
    guardando.value = false
  }
}

const confirmarEliminar = () => {
  modo.value = 'eliminar'
  paso.value = 'resumen'
}
const confirmarEliminarRoom = (r) => {
  eliminarConfirm.value = r
}

const ejecutarEliminar = async () => {
  error.value = ''
  guardando.value = true
  try {
    await api.deleteRoom(eliminarConfirm.value.id)
    store.showToast(`"${eliminarConfirm.value.name}" eliminado`, 'success')
    eliminarConfirm.value = null
    busqueda.value = ''
    await cargarRooms()
  } catch (e) {
    error.value = e.message || 'Error al eliminar'
  } finally {
    guardando.value = false
  }
}

cargarRooms()
</script>

<style scoped>
.nuevo-wrap {
  max-width: 560px;
  margin: 0 auto;
  padding: 1.5rem 1rem 3rem;
}

.hero-header {
  text-align: center;
  margin-bottom: 2rem;
  animation: fadeDown .5s ease both;
}
.hero-badge {
  display: inline-block;
  padding: 0.2rem 0.75rem;
  border-radius: 100px;
  font-size: 0.7rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  background: linear-gradient(135deg, #dbeafe, #ede9fe);
  color: #4f46e5;
  margin-bottom: 0.5rem;
}
.hero-title {
  margin: 0 0 0.35rem;
  font-size: 1.75rem;
  font-weight: 800;
  background: linear-gradient(135deg, #1e293b 0%, #4f46e5 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}
.hero-desc {
  margin: 0;
  color: #94a3b8;
  font-size: 0.9rem;
}

.modo-selector {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}
.modo-card {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1.15rem 1.25rem;
  background: #fff;
  border: 1.5px solid #e9edf4;
  border-radius: 14px;
  cursor: pointer;
  transition: all .25s cubic-bezier(.22,1,.36,1);
  animation: fadeUp .45s ease both;
  animation-delay: calc(var(--i,0) * 0.08s);
}
.modo-card:nth-child(1) { --i: 0; }
.modo-card:nth-child(2) { --i: 1; }
.modo-card:nth-child(3) { --i: 2; }
.modo-card:hover {
  border-color: var(--accent);
  background: var(--accent-bg);
  box-shadow: 0 8px 28px rgba(0,0,0,0.06), 0 2px 8px rgba(0,0,0,0.03);
  transform: translateY(-2px) scale(1.005);
}
.modo-card:active {
  transform: translateY(0) scale(0.99);
}
.modo-icon {
  width: 48px;
  height: 48px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  background: var(--accent-bg);
  color: var(--accent);
  transition: all .25s;
}
.modo-card:hover .modo-icon {
  background: #fff;
  box-shadow: 0 0 0 2px var(--accent);
}
.modo-body {
  flex: 1;
  min-width: 0;
}
.modo-body h3 {
  margin: 0 0 0.15rem;
  font-size: 1rem;
  font-weight: 700;
  color: #0f172a;
}
.modo-body p {
  margin: 0;
  font-size: 0.8rem;
  color: #94a3b8;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.modo-arrow {
  color: #cbd5e1;
  flex-shrink: 0;
  transition: all .25s;
}
.modo-card:hover .modo-arrow {
  color: var(--accent);
  transform: translateX(3px);
}

.summary-card {
  background: #fff;
  border: 1.5px solid var(--accent, #3b82f6);
  border-radius: 16px;
  padding: 1.75rem;
  text-align: center;
  box-shadow: 0 4px 24px rgba(0,0,0,0.04), 0 0 0 1px rgba(59,130,246,0.06);
  animation: fadeUp .35s ease both;
}
.summary-top {
  display: flex;
  margin-bottom: 0.75rem;
}
.summary-icon {
  width: 56px;
  height: 56px;
  border-radius: 14px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 1rem;
  background: var(--accent-bg);
  color: var(--accent);
}
.summary-title {
  margin: 0 0 0.3rem;
  font-size: 1.25rem;
  font-weight: 800;
  color: #0f172a;
}
.summary-desc {
  margin: 0 0 1.25rem;
  font-size: 0.85rem;
  color: #94a3b8;
}
.summary-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 0.5rem;
  background: #f8fafc;
  border: 1px solid #e9edf4;
  border-radius: 12px;
  padding: 1rem;
  margin-bottom: 1.25rem;
}
.summary-grid > div {
  text-align: center;
}
.sg-n {
  font-size: 1.5rem;
  font-weight: 800;
  color: #0f172a;
  line-height: 1;
}
.sg-l {
  font-size: 0.65rem;
  color: #94a3b8;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.3px;
  margin-top: 0.25rem;
}
.sg-bar {
  height: 3px;
  background: #e2e8f0;
  border-radius: 2px;
  margin: 0.5rem 0.25rem 0;
  overflow: hidden;
}
.sg-fill {
  height: 100%;
  border-radius: 2px;
  transition: width .6s cubic-bezier(.4,0,.2,1);
}
.summary-actions {
  display: flex;
  gap: 0.75rem;
}

.section-top {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  margin-bottom: 1.25rem;
  animation: fadeDown .35s ease both;
}
.section-title {
  margin: 0;
  font-size: 1.15rem;
  font-weight: 700;
  color: #0f172a;
}

.btn-back {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  padding: 0.45rem 0.9rem 0.45rem 0.6rem;
  border: none;
  border-radius: 100px;
  background: #f1f5f9;
  cursor: pointer;
  color: #475569;
  font-size: 0.8rem;
  font-weight: 600;
  transition: all .15s;
  flex-shrink: 0;
}
.btn-back:hover {
  background: #e2e8f0;
  color: #0f172a;
}
.btn-back:active {
  transform: scale(.95);
}

.nuevo-form {
  background: #fff;
  border: 1.5px solid #e9edf4;
  border-radius: 16px;
  padding: 1.5rem;
  display: flex;
  flex-direction: column;
  gap: 1rem;
  box-shadow: 0 4px 16px rgba(0,0,0,0.03);
  animation: fadeUp .35s ease both;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 0.35rem;
  flex: 1;
}
.form-label {
  font-size: 0.78rem;
  font-weight: 700;
  color: #334155;
  text-transform: uppercase;
  letter-spacing: 0.03em;
}
.label-opt {
  font-weight: 400;
  color: #94a3b8;
  text-transform: none;
  letter-spacing: 0;
}
.input-wrap {
  position: relative;
  display: flex;
  align-items: center;
}
.input-icon {
  position: absolute;
  left: 0.75rem;
  color: #94a3b8;
  pointer-events: none;
  flex-shrink: 0;
}
.form-input {
  padding: 0.6rem 0.75rem 0.6rem 2.25rem;
  border: 1.5px solid #e9edf4;
  border-radius: 10px;
  font-size: 0.88rem;
  background: #f8fafc;
  color: #0f172a;
  width: 100%;
  transition: all .15s;
}
.form-input:focus {
  outline: none;
  border-color: var(--accent, #3b82f6);
  background: #fff;
  box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
}
select.form-input {
  cursor: pointer;
  appearance: none;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 0.7rem center;
  padding-right: 2.25rem;
}

.autocomplete-group {
  position: relative;
}
.autocomplete-list {
  position: absolute;
  top: calc(100% + 0.15rem);
  left: 0;
  right: 0;
  z-index: 50;
  margin: 0;
  padding: 0.3rem 0;
  list-style: none;
  background: #fff;
  border: 1.5px solid #e9edf4;
  border-radius: 10px;
  box-shadow: 0 8px 28px rgba(0,0,0,0.08);
  max-height: 200px;
  overflow-y: auto;
}
.autocomplete-list li {
  padding: 0.45rem 0.85rem;
  font-size: 0.85rem;
  color: #0f172a;
  cursor: pointer;
  transition: background .1s;
}
.autocomplete-list li:hover {
  background: #eff6ff;
}

.form-row {
  display: flex;
  gap: 0.75rem;
}

.tipo-selector {
  display: flex;
  gap: 0.5rem;
}
.tipo-opt {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.4rem;
  padding: 0.55rem 0.5rem;
  border: 1.5px solid #e9edf4;
  border-radius: 10px;
  font-size: 0.85rem;
  font-weight: 600;
  color: #64748b;
  cursor: pointer;
  transition: all .15s;
  background: #f8fafc;
}
.tipo-opt input { display: none; }
.tipo-opt.active {
  border-color: var(--accent, #3b82f6);
  background: var(--accent-bg, #eff6ff);
  color: var(--accent, #3b82f6);
}
.tipo-opt:not(.active):hover {
  border-color: #cbd5e1;
  background: #f1f5f9;
}

.form-actions {
  display: flex;
  gap: 0.75rem;
  margin-top: 0.35rem;
}
.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 0.4rem;
  padding: 0.65rem 1.35rem;
  border: none;
  border-radius: 10px;
  font-size: 0.88rem;
  font-weight: 700;
  cursor: pointer;
  transition: all .15s;
}
.btn-primary {
  flex: 1;
  background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
  color: #fff;
  box-shadow: 0 4px 12px rgba(59,130,246,0.25);
}
.btn-primary:hover:not(:disabled) {
  box-shadow: 0 6px 20px rgba(59,130,246,0.35);
  transform: translateY(-1px);
}
.btn-primary:active:not(:disabled) {
  transform: translateY(0);
  box-shadow: 0 2px 8px rgba(59,130,246,0.25);
}
.btn-primary:disabled {
  opacity: .45;
  cursor: not-allowed;
}
.btn-danger {
  flex: 1;
  background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
  color: #fff;
  box-shadow: 0 4px 12px rgba(239,68,68,0.25);
}
.btn-danger:hover:not(:disabled) {
  box-shadow: 0 6px 20px rgba(239,68,68,0.35);
  transform: translateY(-1px);
}
.btn-danger:active:not(:disabled) {
  transform: translateY(0);
}
.btn-ghost {
  background: transparent;
  color: #64748b;
  border: 1.5px solid #e9edf4;
}
.btn-ghost:hover {
  background: #f8fafc;
  color: #0f172a;
  border-color: #cbd5e1;
}

.form-msg {
  padding: 0.65rem 0.9rem;
  border-radius: 10px;
  font-size: 0.82rem;
  font-weight: 500;
}
.form-msg.error {
  background: #fef2f2;
  color: #dc2626;
  border: 1px solid #fecaca;
}
.form-msg.exito {
  background: #f0fdf4;
  color: #16a34a;
  border: 1px solid #bbf7d0;
}

.form-divider {
  height: 1px;
  background: #e9edf4;
  margin: 0.15rem 0;
}

.lista-rooms {
  display: flex;
  flex-direction: column;
  gap: 0.4rem;
  max-height: 260px;
  overflow-y: auto;
}
.room-item {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.7rem 0.85rem;
  background: #f8fafc;
  border: 1.5px solid #e9edf4;
  border-radius: 10px;
  cursor: pointer;
  transition: all .15s;
}
.room-item:hover {
  border-color: var(--accent, #3b82f6);
  background: #eff6ff;
  transform: translateX(3px);
}
.room-item.active {
  border-color: var(--accent, #3b82f6);
  background: #eff6ff;
  box-shadow: 0 0 0 2px rgba(59,130,246,0.12);
}
.room-item-del:hover {
  border-color: #dc2626;
  background: #fef2f2;
}
.room-avatar {
  width: 36px;
  height: 36px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.room-avatar.llamado {
  background: #dbeafe;
  color: #2563eb;
}
.room-avatar.bano {
  background: #e0e7ff;
  color: #4338ca;
}
.room-avatar.del {
  background: #fee2e2;
  color: #dc2626;
}
.room-item-info {
  flex: 1;
  min-width: 0;
}
.room-item-info strong {
  display: block;
  font-size: 0.85rem;
  font-weight: 700;
  color: #0f172a;
}
.room-meta {
  font-size: 0.72rem;
  color: #94a3b8;
}
.room-chevron {
  flex-shrink: 0;
  color: #cbd5e1;
}
.room-item:hover .room-chevron {
  color: #94a3b8;
}
.sin-resultados {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.5rem;
  color: #94a3b8;
  font-size: 0.85rem;
  padding: 1.5rem 1rem;
  text-align: center;
}

.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(15,23,42,0.35);
  backdrop-filter: blur(4px);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  padding: 1rem;
}
.modal-confirm {
  background: #fff;
  border-radius: 16px;
  padding: 2rem;
  max-width: 400px;
  width: 100%;
  box-shadow: 0 24px 64px rgba(0,0,0,0.15);
  text-align: center;
}
.modal-icon-wrap {
  width: 56px;
  height: 56px;
  border-radius: 50%;
  background: #fef2f2;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 1rem;
}
.modal-confirm h3 {
  margin: 0 0 0.5rem;
  font-size: 1.1rem;
  font-weight: 700;
  color: #0f172a;
}
.modal-confirm p {
  margin: 0 0 1.35rem;
  font-size: 0.85rem;
  color: #64748b;
  line-height: 1.5;
}

.fade-enter-active, .fade-leave-active {
  transition: opacity .25s ease, transform .25s ease;
}
.fade-enter-from { opacity: 0; transform: translateY(12px); }
.fade-leave-to { opacity: 0; transform: translateY(-12px); }

.slide-up-enter-active {
  transition: all .3s cubic-bezier(.22,1,.36,1);
}
.slide-up-leave-active {
  transition: all .2s ease;
}
.slide-up-enter-from { opacity: 0; transform: translateY(16px); }
.slide-up-leave-to { opacity: 0; transform: translateY(8px); }

.list-enter-active {
  transition: all .25s ease;
}
.list-leave-active {
  transition: all .15s ease;
}
.list-enter-from { opacity: 0; transform: translateY(8px); }
.list-leave-to { opacity: 0; transform: translateY(-4px); }

.msg-enter-active, .msg-leave-active {
  transition: all .2s ease;
}
.msg-enter-from, .msg-leave-to {
  opacity: 0; transform: translateY(4px);
}

.modal-enter-active {
  transition: all .25s cubic-bezier(.22,1,.36,1);
}
.modal-leave-active {
  transition: all .2s ease;
}
.modal-enter-from, .modal-leave-to {
  opacity: 0;
}
.modal-enter-from .modal-confirm {
  transform: scale(.92);
}
.modal-leave-to .modal-confirm {
  transform: scale(.96);
}

@keyframes fadeUp {
  from { opacity: 0; transform: translateY(16px); }
  to { opacity: 1; transform: translateY(0); }
}
@keyframes fadeDown {
  from { opacity: 0; transform: translateY(-10px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>
