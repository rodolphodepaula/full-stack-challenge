import { Component, OnInit } from '@angular/core';
import { ApiService } from 'src/app/services/api.service';
import { Router } from '@angular/router';
import { ToastService } from 'src/app/services/toast.service';
import { HttpParams } from '@angular/common/http';

@Component({
  selector: 'app-tracks',
  templateUrl: './tracks.component.html',
  styleUrls: ['./tracks.component.scss']
})
export class TracksComponent implements OnInit {
  list = [];
  albumList = [];
  artistList = [];
  selectedArtistUuids: string[] = [];

  search: string = "";
  limit: number = 10;
  start: number = 0;

  currentPage: number = 1;
  totalPages: number;
  itemsPerPage: number;
  totalItems: number;
  data: any[] = [];

  uuid: string = "";
  album_thumb: string = "";
  album_uuid: string = "";
  release_date: string = "";
  title: string = "";
  artists: string = "";
  artist_uuid: string = "";
  duration: string = "";
  spotify_url: string = "";
  preview_url: string = "";
  isrc: string = "";
  created_at: string = "";
  available_in_brazil: boolean = false;
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
    this.albumList = [];
    this.artistList = [];
    this.search = "";
    this.start = 0;
    this.loginError = false;
    this.errorMessage = "";
    this.onClearData();
    this.onLoadTracks();
    this.onLoadAlbuns();
    this.onLoadArtists();
    this.onCloseModal();
  }

  onClearData() {
    this.album_thumb = "";
    this.album_uuid = "";
    this.release_date = "";
    this.title = "";
    this.artists = "";
    this.artist_uuid = "";
    this.duration = "";
    this.spotify_url = "";
    this.preview_url = "";
    this.isrc = "";
    this.created_at = "";
    this.available_in_brazil = false;
  }

  onOpenModal() {
    this.isModalOpen = true;
  }

  onCloseModal() {
    this.isModalOpen = false;
  }

  onEditTrack(track: any) {
    this.uuid = track.uuid,
    this.album_uuid = track.album_uuid;
    this.release_date = new Date(track.release_date).toISOString().split('T')[0];
    this.title = track.title;
    this.selectedArtistUuids = track.artist_uuid;
    this.duration = track.duration;
    this.spotify_url = track.spotify_url;
    this.preview_url = track.preview_url;
    this.isrc = track.isrc;
    this.created_at = track.created_at;
    this.available_in_brazil = track.available_in_br;
  }

  setTrackForDeletion(uuid: string) {
    this.uuid = uuid;
  }

  playPreview(previewUrl: string): void {
    if (!previewUrl) {
      console.error('Não há URL de prévia disponível para esta faixa.');
      return;
    }

    const audio = new Audio(previewUrl);
    audio.play().catch(error => console.error('Erro ao tocar a prévia', error));
  }

  onDelete() {
    if (this.uuid) {
      return new Promise(resolve => {
        this.provider.ApiDelete(this.uuid, 'tracks').subscribe(data => {
          this.onCloseModal();
          this.router.navigateByUrl('/', { skipLocationChange: true }).then(() => {
            this.router.navigate(['/admin/tracks']);
          });
          this.toastService.showToast('Faixa musical excluído com sucesso.', 3000);
        },
        error => {
          this.toastService.showToast('Houve um erro ao excluir o usuário.', 3000);
          return;
        });
      });
    }
  }

  onLoadTracks(page: number = 1) {
    this.list = [];
    let params = new HttpParams()
      .set('param', 'search')
      .set('search', this.search)
      .set('page', page.toString());

    return new Promise(resolve => {
      this.provider.ApiGet('tracks',  { params: params }).subscribe(data => {
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

  onLoadAlbuns() {
    this.albumList = [];
    return new Promise(resolve => {
      const params = new HttpParams().set('search', '');
      this.provider.ApiGet('albums', { params }).subscribe(data => {
        for (const dado of data['data']) {
          this.albumList.push(dado)
        }
        resolve(true);
      });
    });
  }

  onLoadArtists() {
    this.artistList = [];
    return new Promise(resolve => {
      const params = new HttpParams().set('search', '');
      this.provider.ApiGet('artists', { params }).subscribe(data => {
        for (const dado of data['data']) {
          this.artistList.push(dado)
        }
        resolve(true);
        console.log(this.artistList);
      });
    });
  }

  openImageModal(imagePath: string) {
    this.selectedImage = imagePath;
  }

  formatArtists(artists: any[]): string {
    return artists.map(artist => artist.name).join(', ');
  }

  onSubmit() {
    return new Promise(resolve => {
      const track = {
        uuid: this.uuid,
        album_uuid: this.album_uuid,
        release_date: this.release_date,
        title: this.title,
        artist_uuids: this.selectedArtistUuids,
        duration: this.duration,
        spotify_url: this.spotify_url,
        preview_url: this.preview_url,
        isrc: this.isrc,
        created_at: this.created_at,
        available_in_brazil: this.available_in_brazil,
      }
      const apiRouter = this.uuid.length > 0 ? 'tracks/' + this.uuid : 'tracks'
      this.provider.ApiPost(track, apiRouter, this.uuid.length === 0)
        .subscribe(
          data => {
            this.onCloseModal();
            this.router.navigateByUrl('/', { skipLocationChange: true }).then(() => {
              this.router.navigate(['/admin/tracks']);
            });
            this.toastService.showToast('Faixa musical salvo com sucesso.', 3000);
          },
          error => {
            this.loginError = true;
            this.errorMessage = 'Houve uma erro no seu cadastro. Por favor, revise e tente novamente!'
            return;
          });
    });
  }
}
