import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { AuthService } from '../services/auth.service';
import { ToastService } from '../services/toast.service';
import { ApiService } from 'src/app/services/api.service';

@Component({
  selector: 'app-signup',
  templateUrl: './signup.component.html',
  styleUrls: ['./signup.component.scss']
})

export class SignupComponent implements OnInit {
  email: string;
  name: string;
  password: string;
  passwordConfirm: string;
  company_uuid: string;
  errorMessage: string;
  loginError: boolean = false;
  companies = [];

  constructor(
    private authService: AuthService,
    private router: Router,
    private toastService: ToastService,
    private provider: ApiService,
  ) { }

  ngOnInit() {
    this.onLoadCompanies();
    //Se estiver logado, redireciona para a home
    if (this.authService.currentUserValue) {
      this.router.navigate(['/']);
    }
  }

  onLoadCompanies() {
    this.companies = [];
    return new Promise(resolve => {
      this.provider.ApiGet('companies/list', {}).subscribe(data => {
        for (const dado of data['data']) {
          this.companies.push(dado)
        }
        resolve(true);
      });
    });
  }

  onSignup(): void {
    if (this.password !== this.passwordConfirm) {
      this.errorMessage = 'A senha informada não coincide com a confirmação. Por favor, verifique e tente novamente!';
      return;
    }
    this.authService.singup(this.name, this.email, this.password, this.passwordConfirm, this.company_uuid)
      .subscribe({
        next: data => {
          // Lógica após o registro bem-sucedido
          this.router.navigate(['/']);
          this.toastService.showToast('Cadastro concluído com sucesso! Realize o login e comece a explorar.', 3000);
        },
        error: error => {
          this.loginError = true;
          this.errorMessage = 'Ops! Algo deu errado na autenticação. Verifique e tente novamente!';
        }
      });

  }
}
