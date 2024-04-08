import { Component, OnInit } from '@angular/core';
import { ApiService } from 'src/app/services/api.service';
import { Router } from '@angular/router';
import { ToastService } from 'src/app/services/toast.service';
import { HttpParams } from '@angular/common/http';

@Component({
  selector: 'app-companies',
  templateUrl: './companies.component.html',
  styleUrls: ['./companies.component.scss']
})
export class CompaniesComponent implements OnInit {
  list = [];
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
  code: string = "";
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
    this.search = "";
    this.start = 0;
    this.loginError = false;
    this.errorMessage = "";
    this.onClearData();
    this.onLoadCompanies();
    this.onCloseModal();
  }

  onClearData() {
    this.name = "";
    this.code = "";
    this.status = false;
  }

  onOpenModal() {
    this.isModalOpen = true;
  }

  onCloseModal() {
    this.isModalOpen = false;
  }

  onEditCompany(company: any) {

    this.uuid = company.uuid;
    this.name = company.name;
    this.code = company.code;
    this.status = company.status;
  }

  setCompanyForDeletion(uuid: string) {
    this.uuid = uuid;
  }

  onDelete() {
    if (this.uuid) {
      return new Promise(resolve => {
        this.provider.ApiDelete(this.uuid, 'companies').subscribe(data => {
          this.onCloseModal();
          this.router.navigateByUrl('/', { skipLocationChange: true }).then(() => {
            this.router.navigate(['/admin/companies']);
          });
          this.toastService.showToast('Empresa excluído com sucesso.', 3000);
        },
        error => {
          this.toastService.showToast('Houve um erro ao excluir o Empresa.', 3000);
          return;
        });
      });
    }
  }

  onLoadCompanies(page: number = 1) {
    this.list = [];
    let params = new HttpParams()
      .set('param', 'search')
      .set('search', this.search)
      .set('page', page.toString());

    return new Promise(resolve => {
      this.provider.ApiGet('companies', { params: params }).subscribe(data => {
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

  onSubmit() {
    return new Promise(resolve => {
      const company = {
        uuid: this.uuid,
        name: this.name,
        code: this.code,
        status: this.status
      }
      const apiRouter = this.uuid.length > 0 ? 'companies/' + this.uuid : 'companies'
      this.provider.ApiPost(company, apiRouter, this.uuid.length === 0)
        .subscribe(
          data => {
            this.onCloseModal();
            this.router.navigateByUrl('/', { skipLocationChange: true }).then(() => {
              this.router.navigate(['/admin/companies']);
            });
            this.toastService.showToast('Empresa salvo com sucesso.', 3000);
          },
          error => {
            this.loginError = true;
            this.errorMessage = 'Houve uma erro no seu cadastro. Por favor, revise e tente novamente!'
            return;
          });
    });
  }
}
