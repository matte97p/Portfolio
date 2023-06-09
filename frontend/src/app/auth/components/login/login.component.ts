import { Component } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { AbstractControl } from '@angular/forms';
import { AuthService } from '../../services/auth.service';
import { MessagesService } from 'src/app/shared/services/messages.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent {

  form: FormGroup = new FormGroup({
    email: new FormControl('', [Validators.required]),
    password: new FormControl('', [Validators.required])
  });

  submitted = false;
  message: string | undefined;

  get f(): { [key: string]: AbstractControl } {
    return this.form.controls;
  }

  constructor(
    private authService: AuthService,
    private _messagesService: MessagesService,
  ) {
    localStorage.clear();
  }

  login(){
    this.submitted = true;

    if (this.form.invalid) {
      return;
    }

    this.authService.login(this.form.value).subscribe(
      (res: any) => {},
      (err: any) => {
        this.form.reset()
      }
    );
  }

  showResponse(response: any) {
    this._messagesService.success('Robot', 'Captcha valido!');
    return true;
  }
}
