import { Component, OnInit } from '@angular/core';
import { TrackService } from '../services/track.service';
import { AuthService } from '../services/auth.service';
import { Router } from '@angular/router';
import { Observable } from 'rxjs';

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
  isLoggedIn$: Observable<boolean>;

  constructor(private trackService: TrackService,
    private authService: AuthService,
    private router: Router
  ) {}

  ngOnInit(): void {
    this.isLoggedIn$ = this.authService.isLoggedIn();
  }

  onSearch(): void {
    this.errorMessage = '';
    this.track = null;

    if (this.isrcCode) {
      this.trackService.getTrackByISRC(this.isrcCode).subscribe({
        next: (response) => {
          if (response.data && response.data.length > 0) {
            this.tracks = response.data;
            this.currentTrackIndex = 0;
            this.track = this.tracks[this.currentTrackIndex];
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
      this.updateCurrentTrack();
    }
  }
  onNext(): void {
    if (this.currentTrackIndex < this.tracks.length - 1) {
      this.currentTrackIndex++;
      this.updateCurrentTrack();

    }
  }
  updateCurrentTrack(): void {
    if (this.currentTrackIndex >= 0 && this.currentTrackIndex < this.tracks.length) {
      this.track = this.tracks[this.currentTrackIndex];
    } else {
      this.track = null;
    }
  }

  addToLibrary(track: any): void
  {
    this.isLoggedIn$.subscribe(isLoggedIn => {
      if (isLoggedIn) {
        // Lógica para adicionar à biblioteca...
      } else {
        this.router.navigate(['/signup']);
      }
    });

  }
}
