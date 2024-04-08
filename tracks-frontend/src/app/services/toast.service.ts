import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class ToastService {
  constructor() { }

  showToast(message: string, duration: number = 3000) {
    const toastContainer = document.createElement('div');
    toastContainer.classList.add('toast-container');

    const toast = document.createElement('div');
    toast.classList.add('toast', 'show');

    toast.innerHTML = `
      <div class="toast-header">
        <strong class="mr-auto">Notificação</strong>
        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast">&times;</button>
      </div>
      <div class="toast-body">
        ${message}
      </div>
    `;

    toast.addEventListener('click', () => {
      toast.classList.remove('show');
      setTimeout(() => toast.remove(), 150);
    });

    toastContainer.appendChild(toast);
    document.body.appendChild(toastContainer);

    setTimeout(() => {
      toast.classList.remove('show');
      setTimeout(() => toast.remove(), 150);
    }, duration);
  }
}
