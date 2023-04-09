import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { HttpHeaders } from '@angular/common/http';

import { tap } from 'rxjs/operators';
import { MessagesService } from 'src/app/shared/services/messages.service';

@Injectable({
  providedIn: 'root',
})
export class AuthService {

  private backend_api = 'https://backend-portfolio.test/';

  constructor(
    private _http: HttpClient,
    private messagesService: MessagesService
  ) {
  }

}
