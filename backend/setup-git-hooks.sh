#!/bin/sh

# Copy the pre-commit hook to the .git/hooks directory
cp .githooks/pre-commit .git/hooks/pre-commit

# Ensure the hook is executable
chmod +x .git/hooks/pre-commit

echo "Git hooks installed."
