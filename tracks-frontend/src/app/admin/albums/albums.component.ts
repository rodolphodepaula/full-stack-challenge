import { Component, OnInit } from '@angular/core';
import { ApiService } from 'src/app/services/api.service';
import { Router } from '@angular/router';
import { ToastService } from 'src/app/services/toast.service';
import { HttpParams } from '@angular/common/http';

@Component({
  selector: 'app-albums',
  templateUrl: './albums.component.html',
  styleUrls: ['./albums.component.scss']
})
export class AlbumsComponent implements OnInit {
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
  title: string = "";
  thumb_path: string = "";
  company_uuid: string = "";
  errorMessage: string = "";
  loginError: boolean = false;
  isModalOpen: boolean = false;
  selectedImage: string = "";

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
    this.onLoadAlbums();
    this.onLoadCompanies();
    this.onCloseModal();
  }

  onClearData() {
    this.title = "";
    this.thumb_path = "";
    this.company_uuid = "";
    this.companies = [];
  }

  onOpenModal() {
    this.isModalOpen = true;
  }

  onCloseModal() {
    this.isModalOpen = false;
  }

  onEditalbum(album: any) {

    this.uuid = album.uuid;
    this.title = album.title;
    this.thumb_path = album.thumb_path;
    this.company_uuid = album.company_uuid;
  }

  setAlbumForDeletion(uuid: string) {
    this.uuid = uuid;
  }

  openImageModal(imagePath: string) {
    this.selectedImage = imagePath;
  }

  onDelete() {
    if (this.uuid) {
      return new Promise(resolve => {
        this.provider.ApiDelete(this.uuid, 'albums').subscribe(data => {
          this.onCloseModal();
          this.router.navigateByUrl('/', { skipLocationChange: true }).then(() => {
            this.router.navigate(['/admin/albums']);
          });
          this.toastService.showToast('Album excluído com sucesso.', 3000);
        },
        error => {
          this.toastService.showToast('Houve um erro ao excluir o Album.', 3000);
          return;
        });
      });
    }
  }

  onLoadAlbums(page: number = 1) {
    this.list = [];
    let params = new HttpParams()
      .set('param', 'search')
      .set('search', this.search)
      .set('page', page.toString());

    return new Promise(resolve => {
      this.provider.ApiGet('albums', { params: params }).subscribe(data => {
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
      const album = {
        uuid: this.uuid,
        title: this.title,
        thumb_path: this.thumb_path,
        company_uuid: this.company_uuid,
      }
      const apiRouter = this.uuid.length > 0 ? 'albums/' + this.uuid : 'albums'
      this.provider.ApiPost(album, apiRouter, this.uuid.length === 0)
        .subscribe(
          data => {
            this.onCloseModal();
            this.router.navigateByUrl('/', { skipLocationChange: true }).then(() => {
              this.router.navigate(['/admin/albums']);
            });
            this.toastService.showToast('Album salvo com sucesso.', 3000);
          },
          error => {
            this.loginError = true;
            this.errorMessage = 'Houve uma erro no seu cadastro. Por favor, revise e tente novamente!'
            return;
          });
    });
  }
}
