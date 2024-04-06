import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { AuthService } from '../services/auth.service';
import { ToastService } from '../services/toast.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})

export class LoginComponent implements OnInit {
  email: string;
  password: string;
  errorMessage: string;
  loginError: boolean = false;

  constructor(
    private authService: AuthService,
    private router: Router,
    private toastService: ToastService
  ) {}

  ngOnInit() {
    //Se estiver logado, redireciona para a home
    if (this.authService.currentUserValue) {
      this.router.navigate(['/']);
    }
  }

  onLogin(): void {
    this.authService.login(this.email, this.password).subscribe(
        data => {
          this.router.navigate(['/admin']);
          this.toastService.showToast('Bem-vindo ao painel ONERpm!', 3000);
        },
        error => {
          this.loginError = true;
          console.error(error);

          if (!this.email || !this.password) {
            this.errorMessage = 'Login não realizado. Confira suas credenciais e tente de novo!';
            return;
          }

          this.errorMessage = 'Ops! Algo deu errado na autenticação. Verifique e tente novamente!';

        });
  }
}
