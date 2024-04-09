import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { BehaviorSubject, Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class TrackService {
  private apiUrl = 'http://localhost/api/tracks/search';
  private trackSource = new BehaviorSubject<any>(null);
  currentTrack = this.trackSource.asObservable();

  constructor(private http: HttpClient) { }

  getTrackByISRC(isrc: string): Observable<any> {
    return this.http.get(`${this.apiUrl}/${isrc}`);
  }

  changeTrack(track: any) {
    this.trackSource.next(track);
  }
}
