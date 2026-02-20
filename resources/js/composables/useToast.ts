import Swal from 'sweetalert2'

export function useToast() {
  const getIconHtml = (type: 'success' | 'error' | 'info' | 'warning') => {
    const icons = {
      success: `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg>`,
      error: `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/></svg>`,
      warning: `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>`,
      info: `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>`,
    }
    return icons[type]
  }

  const getIconColor = (type: 'success' | 'error' | 'info' | 'warning') => {
    const colors = {
      success: 'hsl(var(--success))',
      error: 'hsl(var(--destructive))',
      warning: 'hsl(var(--warning))',
      info: 'hsl(var(--primary))',
    }
    return colors[type]
  }

  const toast = (message: string, type: 'success' | 'error' | 'info' | 'warning' = 'success') => {
    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true,
      didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
      },
      customClass: {
        popup: 'swal-toast-popup',
        htmlContainer: 'swal-toast-content',
        timerProgressBar: 'swal-toast-progress',
      },
      background: 'hsl(var(--popover))',
      color: 'hsl(var(--popover-foreground))',
      showClass: {
        popup: 'swal2-show',
      },
      hideClass: {
        popup: 'swal2-hide',
      },
    })

    Toast.fire({
      html: `
        <div class="swal-toast-wrapper">
          <div class="swal-toast-icon" style="color: ${getIconColor(type)}">
            ${getIconHtml(type)}
          </div>
          <div class="swal-toast-message">${message}</div>
        </div>
      `,
    })
  }

  return {
    toast,
    success: (message: string) => toast(message, 'success'),
    error: (message: string) => toast(message, 'error'),
    info: (message: string) => toast(message, 'info'),
    warning: (message: string) => toast(message, 'warning'),
  }
}
