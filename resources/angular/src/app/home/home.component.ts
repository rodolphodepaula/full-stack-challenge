import { Component, OnInit } from '@angular/core';
import { TrackService } from '../services/track.service';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.scss']
})
export class HomeComponent implements OnInit {
  isrcCode: string = '';
  track: any = null;
  tracks: any[] = [];
  errorMessage: string = '';
  currentTrackIndex: number = 0;

  constructor(private trackService: TrackService) {}

  ngOnInit(): void {}

  onSearch(): void {
    this.errorMessage = '';
    this.track = null;

    if (this.isrcCode) {
      this.trackService.getTrackByISRC(this.isrcCode).subscribe({
        next: (response) => {
          if (response.data && response.data.length > 0) {
            this.track = response.data[0];
          } else {
            this.errorMessage = 'Ops! Não encontramos resultados para o ISRC informado. Tente novamente!';
          }
        },
        error: (error) => {
          console.error('Erro ao buscar a faixa', error);
          this.errorMessage = 'Oops! Encontramos um obstáculo na busca pela faixa. Tente novamente.';
        }
      });
    } else {
      this.errorMessage = 'Vamos lá! Insira um código ISRC válido para prosseguir';
    }
  }
  onPrevious(): void {
    if (this.currentTrackIndex > 0) {
      this.currentTrackIndex--;

    }
  }
  onNext(): void {
    if (this.currentTrackIndex < this.tracks.length - 1) {
      this.currentTrackIndex++;

    }
  }
}
