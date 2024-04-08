import { Component, OnInit } from '@angular/core';
import { ApiService } from 'src/app/services/api.service';
import { Router } from '@angular/router';
import { ToastService } from 'src/app/services/toast.service';
import { HttpParams } from '@angular/common/http';

@Component({
  selector: 'app-users',
  templateUrl: './users.component.html',
  styleUrls: ['./users.component.scss']
})
export class UsersComponent implements OnInit {
  list = [];
  companies = [];
  search: string = "";
  limit: number = 10;
  start: number = 0;
  currentPage: number = 1;
  totalPages: number;
  itemsPerPage: number;
  totalItems: number;
  data: any[] = [];
  uuid: string = "";
  name: string = "";
  email: string = "";
  company_uuid: string = "";
  status: boolean = false;
  errorMessage: string = "";
  loginError: boolean = false;
  isModalOpen: boolean = false;

  constructor(
    private provider: ApiService,
    private router: Router,
    private toastService: ToastService
  ) { }

  ngOnInit() {
    this.onClear();

  }

  onClear() {
    this.list = [];
    this.companies = [];
    this.search = "";
    this.start = 0;
    this.loginError = false;
    this.errorMessage = "";
    this.onClearData();
    this.onLoadUsers();
    this.onLoadCompanies();
    this.onCloseModal();
  }

  onClearData() {
    this.name = "";
    this.email = "";
    this.company_uuid = "";
    this.status = false;
    this.companies = [];
  }

  onOpenModal() {
    this.isModalOpen = true;
  }

  onCloseModal() {
    this.isModalOpen = false;
  }

  onEditUser(user: any) {

    this.uuid = user.uuid;
    this.name = user.name;
    this.email = user.email;
    this.company_uuid = user.company_uuid;
    this.status = user.status;
  }

  setUserForDeletion(uuid: string) {
    this.uuid = uuid;
  }

  onDelete() {
    if (this.uuid) {
      return new Promise(resolve => {
        this.provider.ApiDelete(this.uuid, 'users').subscribe(data => {
          this.onCloseModal();
          this.router.navigateByUrl('/', { skipLocationChange: true }).then(() => {
            this.router.navigate(['/admin/users']);
          });
          this.toastService.showToast('Usuário excluído com sucesso.', 3000);
        },
        error => {
          this.toastService.showToast('Houve um erro ao excluir o usuário.', 3000);
          return;
        });
      });
    }
  }

  onLoadUsers(page: number = 1) {
    this.list = [];
    let params = new HttpParams()
      .set('param', 'search')
      .set('search', this.search)
      .set('page', page.toString());

    return new Promise(resolve => {
      this.provider.ApiGet('users', { params: params }).subscribe(data => {
        for (const dado of data['data']) {
          this.list.push(dado);
        }
        this.currentPage = data['meta']['current_page'];
        this.totalPages = data['meta']['last_page'];
        this.itemsPerPage = data['meta']['per_page'];
        this.totalItems = data['meta']['total'];
        resolve(true);
      })
    });
  }

  onLoadCompanies() {
    this.companies = [];
    return new Promise(resolve => {
      const params = new HttpParams().set('search', '');
      this.provider.ApiGet('companies', { params }).subscribe(data => {
        for (const dado of data['data']) {
          this.companies.push(dado)
        }
        resolve(true);
      });
    });
  }

  onSubmit() {
    return new Promise(resolve => {
      const user = {
        uuid: this.uuid,
        name: this.name,
        email: this.email,
        company_uuid: this.company_uuid,
        status: this.status
      }
      const apiRouter = this.uuid.length > 0 ? 'users/' + this.uuid : 'users'
      this.provider.ApiPost(user, apiRouter, this.uuid.length === 0)
        .subscribe(
          data => {
            this.onCloseModal();
            this.router.navigateByUrl('/', { skipLocationChange: true }).then(() => {
              this.router.navigate(['/admin/users']);
            });
            this.toastService.showToast('Usuário salvo com sucesso.', 3000);
          },
          error => {
            this.loginError = true;
            this.errorMessage = 'Houve uma erro no seu cadastro. Por favor, revise e tente novamente!'
            return;
          });
    });
  }
}
