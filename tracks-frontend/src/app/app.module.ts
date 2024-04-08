import { BrowserModule } from '@angular/platform-browser';
import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { XyzComponent } from './xyz/xyz.component';
import { HomeComponent } from './home/home.component';
import { AdminComponent } from './admin/admin.component';
import { HttpClientModule } from '@angular/common/http';
import { LoginComponent } from './login/login.component';
import { UsersComponent } from './admin/users/users.component';
import { CompaniesComponent } from './admin/companies/companies.component';
import { TracksComponent } from './admin/tracks/tracks.component';
import { ArtistsComponent } from './admin/artists/artists.component';
import { AlbumsComponent } from './admin/albums/albums.component';
import { DashboardComponent } from './admin/dashboard/dashboard.component';
import { MatDialogModule } from '@angular/material/dialog';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatInputModule } from '@angular/material/input';
import { MatSelectModule } from '@angular/material/select';
import { MatButtonModule } from '@angular/material/button';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { UserDialogComponent } from './admin/users/user-dialog/user-dialog.component';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { MatSlideToggleModule } from '@angular/material/slide-toggle';
import { NgModule } from '@angular/core';
import { MatTableModule } from '@angular/material/table';
import { HttpClient } from '@angular/common/http';
import { ApiService } from './services/api.service';
import { SignupComponent } from './signup/signup.component';

@NgModule({
  declarations: [
    // Seus componentes aqui
  ],
  imports: [
    // Outros módulos aqui
    MatTableModule,
  ],
})
export class SeuModulo { }


@NgModule({
  declarations: [
    AppComponent,
    XyzComponent,
    HomeComponent,
    AdminComponent,
    LoginComponent,
    UsersComponent,
    CompaniesComponent,
    TracksComponent,
    ArtistsComponent,
    AlbumsComponent,
    DashboardComponent,
    UserDialogComponent,
    SignupComponent,

  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    HttpClientModule,
    FormsModule,
    MatDialogModule,
    MatFormFieldModule,
    MatInputModule,
    MatSelectModule,
    MatButtonModule,
    ReactiveFormsModule,
    BrowserAnimationsModule,
    MatSlideToggleModule,
  ],
  providers: [
    ApiService,
    HttpClient
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
