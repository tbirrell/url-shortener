{ pkgs ? import <nixpkgs> {} }:

with pkgs;

mkShell {
  buildInputs = [ 
    php72 
    php72Packages.composer
    sqlite
    httpie
  ];
}
