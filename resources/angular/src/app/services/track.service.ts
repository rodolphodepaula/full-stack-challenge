import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class TrackService {
  private apiUrl = 'http://localhost/api/tracks/search';

  constructor(private http: HttpClient) { }

  getTrackByISRC(isrc: string): Observable<any> {
    return this.http.get(`${this.apiUrl}/${isrc}`);
  }
}
