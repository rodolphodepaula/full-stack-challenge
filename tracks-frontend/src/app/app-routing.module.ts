import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { HomeComponent } from './home/home.component';
import { AdminComponent } from './admin/admin.component';
import { LoginComponent } from './login/login.component';
import { AuthGuard } from './auth.guard';
import { UsersComponent } from './admin/users/users.component';
import { CompaniesComponent } from './admin/companies/companies.component';
import { TracksComponent } from './admin/tracks/tracks.component';
import { ArtistsComponent } from './admin/artists/artists.component';
import { AlbumsComponent } from './admin/albums/albums.component';
import { DashboardComponent } from './admin/dashboard/dashboard.component';
import { SignupComponent } from './signup/signup.component';


const routes: Routes = [
  {path:'', component: HomeComponent},
  {path:'login', component: LoginComponent},
  {path:'signup', component: SignupComponent},
  {
    path:'admin',
    component: AdminComponent,
    canActivate: [AuthGuard],
    children: [
      { path: '', redirectTo: 'dashboard', pathMatch: 'full' },
      {path:'dashboard', component: DashboardComponent},
      {path:'users', component: UsersComponent},
      {path:'companies', component: CompaniesComponent},
      {path:'tracks', component: TracksComponent},
      {path:'artists', component: ArtistsComponent},
      {path:'albums', component: AlbumsComponent},
    ],
  },
];

@NgModule({
  imports: [RouterModule.forRoot(routes, { onSameUrlNavigation: 'reload' })],
  exports: [RouterModule]
})
export class AppRoutingModule { }
