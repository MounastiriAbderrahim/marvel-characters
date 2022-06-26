import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { Characters } from './../model/Characters';

@Injectable({
  providedIn: 'root'
})
export class CharacterService {
  private baseUrl = 'http://127.0.0.1:8741/api/characters';
  list! : Characters[] ;

  constructor(private http: HttpClient) { }

  getAll(): Observable<any> {
    return this.http.get(`${this.baseUrl}`);
  }

}