import { useStore } from './store'
import { api } from './api'

const doRefresh = async () => {
  const store = useStore()
  try {
    const historialData = await api.getHistorial()
    if (historialData.historial.data) {
      store.setHistorial(historialData.historial.data)
    } else if (historialData.historial) {
      store.setHistorial(historialData.historial)
    } else {
      store.setHistorial([])
    }
  } catch (err) {
    console.error('Error al actualizar historial:', err)
  }
}

export const refreshHistorial = doRefresh

let debounceTimer = null
export const refreshHistorialDebounced = () => {
  if (debounceTimer) clearTimeout(debounceTimer)
  debounceTimer = setTimeout(doRefresh, 500)
}