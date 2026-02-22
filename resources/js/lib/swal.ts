import Swal from 'sweetalert2'

const getCssVar = (name: string, fallback: string) => {
  const value = getComputedStyle(document.documentElement).getPropertyValue(name).trim()
  return value || fallback
}

const resolveColor = (value: string, fallback: string) => {
  const v = (value || '').trim()
  if (!v) return fallback
  if (v.startsWith('hsl(') || v.startsWith('rgb(') || v.startsWith('#')) return v
  return `hsl(${v})`
}

export const getSwal = () => {
  const popupBg = getCssVar('--popover', '#ffffff')
  const popupFg = getCssVar('--popover-foreground', '#111111')
  const success = resolveColor(getCssVar('--success', 'hsl(142 76% 36%)'), 'hsl(142 76% 36%)')
  const successFg = resolveColor(getCssVar('--success-foreground', 'hsl(0 0% 98%)'), 'hsl(0 0% 98%)')
  const danger = resolveColor(getCssVar('--destructive', 'hsl(0 84.2% 60.2%)'), 'hsl(0 84.2% 60.2%)')
  const dangerFg = resolveColor(getCssVar('--destructive-foreground', 'hsl(0 0% 98%)'), 'hsl(0 0% 98%)')
  const radius = getCssVar('--radius', '0.5rem')
  const ring = getCssVar('--ring', '0 0% 3.9%')

  return Swal.mixin({
    customClass: {
      popup: 'swal-shadcn-popup',
      title: 'swal-shadcn-title',
      htmlContainer: 'swal-shadcn-html',
      actions: 'swal-shadcn-actions',
      confirmButton: 'swal-shadcn-confirm',
      cancelButton: 'swal-shadcn-cancel',
    },
    buttonsStyling: false,
    background: popupBg,
    color: popupFg,
    didOpen: (popup) => {
      // Force inline priority to avoid third-party overrides.
      popup.style.setProperty('background', popupBg, 'important')
      popup.style.setProperty('color', popupFg, 'important')

      const confirmBtn = popup.querySelector<HTMLButtonElement>('.swal2-confirm')
      if (confirmBtn) {
        confirmBtn.style.setProperty('background', success, 'important')
        confirmBtn.style.setProperty('color', successFg, 'important')
        confirmBtn.style.setProperty('border', `1px solid ${success}`, 'important')
        confirmBtn.style.setProperty('border-radius', `calc(${radius} - 2px)`, 'important')
        confirmBtn.style.setProperty('padding', '0.5rem 1rem', 'important')
        confirmBtn.style.setProperty('font-weight', '600', 'important')
        confirmBtn.style.setProperty('min-width', '112px', 'important')
        confirmBtn.style.setProperty('cursor', 'pointer', 'important')
      }

      const cancelBtn = popup.querySelector<HTMLButtonElement>('.swal2-cancel')
      if (cancelBtn) {
        cancelBtn.style.setProperty('background', danger, 'important')
        cancelBtn.style.setProperty('color', dangerFg, 'important')
        cancelBtn.style.setProperty('border', `1px solid ${danger}`, 'important')
        cancelBtn.style.setProperty('border-radius', `calc(${radius} - 2px)`, 'important')
        cancelBtn.style.setProperty('padding', '0.5rem 1rem', 'important')
        cancelBtn.style.setProperty('font-weight', '600', 'important')
        cancelBtn.style.setProperty('min-width', '112px', 'important')
        cancelBtn.style.setProperty('cursor', 'pointer', 'important')
      }

      const actions = popup.querySelector<HTMLElement>('.swal2-actions')
      if (actions) {
        actions.style.setProperty('gap', '0.5rem', 'important')
        actions.style.setProperty('margin-top', '1rem', 'important')
      }

      const focused = popup.querySelector<HTMLElement>(':focus')
      if (focused) {
        focused.style.setProperty('outline', 'none', 'important')
        focused.style.setProperty('box-shadow', `0 0 0 2px hsl(${ring} / 0.3)`, 'important')
      }
    },
  })
}
