import { Component, OnInit } from '@angular/core';
import { ApiService } from 'src/app/services/api.service';
import { Router } from '@angular/router';
import { ToastService } from 'src/app/services/toast.service';
import { HttpParams } from '@angular/common/http';

@Component({
  selector: 'app-artists',
  templateUrl: './artists.component.html',
  styleUrls: ['./artists.component.scss']
})
export class ArtistsComponent implements OnInit {
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
    this.onLoadArtists();
    this.onCloseModal();
  }

  onClearData() {
    this.name = "";
  }

  onOpenModal() {
    this.isModalOpen = true;
  }

  onCloseModal() {
    this.isModalOpen = false;
  }

  onEditArtist(artist: any) {
    this.uuid = artist.uuid;
    this.name = artist.name;
  }

  setArtistForDeletion(uuid: string) {
    this.uuid = uuid;
  }

  onDelete() {
    if (this.uuid) {
      return new Promise(resolve => {
        this.provider.ApiDelete(this.uuid, 'artists').subscribe(data => {
          this.onCloseModal();
          this.router.navigateByUrl('/', { skipLocationChange: true }).then(() => {
            this.router.navigate(['/admin/artists']);
          });
          this.toastService.showToast('Artista excluído com sucesso.', 3000);
        },
        error => {
          this.toastService.showToast('Houve um erro ao excluir o Artista.', 3000);
          return;
        });
      });
    }
  }

  onLoadArtists(page: number = 1) {
    this.list = [];
    let params = new HttpParams()
      .set('param', 'search')
      .set('search', this.search)
      .set('page', page.toString());

    return new Promise(resolve => {
      this.provider.ApiGet('artists', { params: params }).subscribe(data => {
        for (const dado of data['data']) {
          this.list.push(dado);
        }
        resolve(true);
      })
    });
  }

  onSubmit() {
    return new Promise(resolve => {
      const artist = {
        uuid: this.uuid,
        name: this.name,
      }
      const apiRouter = this.uuid.length > 0 ? 'artists/' + this.uuid : 'artists'
      this.provider.ApiPost(artist, apiRouter, this.uuid.length === 0)
        .subscribe(
          data => {
            this.onCloseModal();
            this.router.navigateByUrl('/', { skipLocationChange: true }).then(() => {
              this.router.navigate(['/admin/artists']);
            });
            this.toastService.showToast('Artista salvo com sucesso.', 3000);
          },
          error => {
            this.loginError = true;
            this.errorMessage = 'Houve uma erro no seu cadastro. Por favor, revise e tente novamente!'
            return;
          });
    });
  }
}
