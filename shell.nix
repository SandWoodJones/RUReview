{
  pkgs ? import <nixpkgs> { config.allowUnfree = true; },
}:
pkgs.mkShell {
  packages = with pkgs; [
    php
    intelephense
    sqlite
  ];
}
