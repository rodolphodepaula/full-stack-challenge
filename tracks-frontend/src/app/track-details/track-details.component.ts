import { Component, OnInit } from '@angular/core';
import { TrackService } from '../services/track.service';
import { Router, ActivatedRoute } from '@angular/router';
import { ToastService } from '../services/toast.service';
import { ApiService } from '../services/api.service';

@Component({
  selector: 'app-track-details',
  templateUrl: './track-details.component.html',
  styleUrls: ['./track-details.component.scss']
})
export class TrackDetailsComponent implements OnInit {
  artistUuids: any[];
  trackDetails: any;
  albumUuid: any;
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

  constructor(
    private provider: ApiService,
    private router: Router,
    private toastService: ToastService
  ) { }

  async ngOnInit(): Promise<void> {
    const track = localStorage.getItem('track');
    this.isrc = localStorage.getItem('isrc');
    if (!track) {
      this.router.navigate(['/']);
      this.toastService.showToast('Ops! Não conseguimos encontrar os detalhes da faixa. Você será redirecionado à página inicial.', 3000);

      return;
    }

    this.trackDetails = JSON.parse(track);
    localStorage.removeItem('isrc');
    localStorage.removeItem('track');

    this.artistUuids = await this.saveArtistsAndGetUuids();
    this.albumUuid = await this.saveAlbum();
  }

  async onSubmit() {
    try {
      const apiRoute = this.uuid.length > 0 ? `tracks/${this.uuid}` : 'tracks';
      const track = {
        album_uuid: this.albumUuid.toString(),
        artist_uuids: this.artistUuids,
        release_date: this.trackDetails.release_date,
        spotify_url: this.trackDetails.spotify_url,
        duration: this.trackDetails.duration,
        preview_url: this.trackDetails.preview_url,
        available_in_brazil: this.trackDetails.available_in_br,
        isrc: this.isrc
      };

      this.provider.ApiPost(track, apiRoute, this.uuid.length === 0 )
      .subscribe(
        data => {
          this.toastService.showToast('Faixa musical salva com sucesso.', 3000);
          this.router.navigateByUrl('/', { skipLocationChange: true }).then(() => {
            this.router.navigate(['/admin/tracks']);
          });
        },
        error => {
          this.toastService.showToast('Houve um erro no seu cadastro. Por favor, revise e tente novamente!', 3000);
        }
      );
    } catch (error) {
      this.toastService.showToast('Houve um erro no processo. Por favor, revise e tente novamente!', 3000);
      console.error('Erro durante a criação de artistas ou álbum:', error);
    }
  }

  async saveArtistsAndGetUuids() {
    const uuid = []
    for (const name of this.trackDetails.artists) {
      const artist = { name };
      try {
        const response = await this.saveArtist(artist);
        uuid.push(response);
      } catch (error) {
        console.error('Erro ao salvar o artista', name, error);
      }
    }
    return uuid;
  }

  saveArtist(artist: any): Promise<any> {
    return new Promise((resolve, reject) => {
      this.provider.ApiPost(artist, 'artists', true)
        .subscribe(
          data => resolve((data as any).data.uuid),
          error => reject(error)
        );
    });
  }

  saveAlbum() {
    const currentUser = localStorage.getItem('currentUser');
    const user = JSON.parse(currentUser);
    return new Promise((resolve, reject) => {
      const album = {
        'title': this.trackDetails.track_title,
        'thumb_path': this.trackDetails.album_thumb,
        'company_uuid': user.company_uuid
      };

      this.provider.ApiPost(album, 'albums', true)
        .subscribe(
          data => resolve((data as any).data.uuid),
          error => reject(error)
        );
    });
  }


}
