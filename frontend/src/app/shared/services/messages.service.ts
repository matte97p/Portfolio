import { HttpErrorResponse } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { MessageService } from 'primeng/api';

@Injectable()
export class MessagesService {
  messages: string[] = [];

  constructor(
    private _messageService: MessageService,
  ) {}

  success(service: string, message: string) {
    this._messageService.add({ severity: 'success', summary: service, detail: JSON.stringify(message), sticky: false, closable: true });
    this.messages.push(message);
  }

  info(service: string, message: string) {
    this._messageService.add({ severity: 'info', summary: service, detail: JSON.stringify(message), sticky: false, closable: true });
    this.messages.push(message);
  }

  error(service: string, error: HttpErrorResponse) {
    this._messageService.add({ severity: 'error', summary: service, detail: error.error.message, sticky: false, closable: true });
    this.messages.push(error.error.error);
  }

  clear() {
    this.messages = [];
  }
}
